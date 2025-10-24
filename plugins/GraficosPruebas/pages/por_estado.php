<?php
auth_ensure_user_authenticated();

layout_page_header(plugin_lang_get('title'));
layout_page_begin();

$t_field_name = 'tipo_prueba';
$t_field_id = custom_field_get_id_from_name($t_field_name);

if ($t_field_id === false) {
    echo '<div class="alert alert-danger">Campo personalizado no encontrado</div>';
    layout_page_end();
    exit;
}

$t_project_id = helper_get_current_project();

$t_custom_field_string_table = db_get_table('custom_field_string');
$t_bug_table = db_get_table('bug');

// =============================
// CONSULTA: Totales
// =============================
db_param_push();
$t_query_total = "
    SELECT cfs.value, COUNT(*) as count
    FROM $t_custom_field_string_table cfs
    INNER JOIN $t_bug_table b ON cfs.bug_id = b.id
    WHERE cfs.field_id = " . db_param() . "
    AND b.project_id = " . db_param() . "
    GROUP BY cfs.value
    ORDER BY count DESC";
$t_result_total = db_query($t_query_total, array($t_field_id, $t_project_id));

// =============================
// CONSULTA: Resueltos
// =============================
db_param_push();
$t_query_resueltos = "
    SELECT cfs.value, COUNT(*) as count
    FROM $t_custom_field_string_table cfs
    INNER JOIN $t_bug_table b ON cfs.bug_id = b.id
    WHERE cfs.field_id = " . db_param() . "
    AND b.project_id = " . db_param() . "
    AND b.status >= 80
    GROUP BY cfs.value
    ORDER BY cfs.value";
$t_result_resueltos = db_query($t_query_resueltos, array($t_field_id, $t_project_id));

// =============================
// PROCESAR DATOS
// =============================
$t_labels = array();
$t_data_total = array();
$t_data_resueltos = array();

$t_totales_map = array();
while ($t_row = db_fetch_array($t_result_total)) {
    $t_totales_map[$t_row['value']] = (int) $t_row['count'];
    if (!in_array($t_row['value'], $t_labels)) {
        $t_labels[] = $t_row['value'];
    }
}

$t_resueltos_map = array();
while ($t_row = db_fetch_array($t_result_resueltos)) {
    $t_resueltos_map[$t_row['value']] = (int) $t_row['count'];
}

foreach ($t_labels as $t_label) {
    $t_data_total[] = isset($t_totales_map[$t_label]) ? $t_totales_map[$t_label] : 0;
    $t_data_resueltos[] = isset($t_resueltos_map[$t_label]) ? $t_resueltos_map[$t_label] : 0;
}

// =============================
// CALCULAR PORCENTAJES
// =============================
$t_porcentajes = array();
for ($i = 0; $i < count($t_data_total); $i++) {
    if ($t_data_total[$i] > 0) {
        $t_porcentajes[] = round(($t_data_resueltos[$i] / $t_data_total[$i]) * 100, 1);
    } else {
        $t_porcentajes[] = 0;
    }
}

// =============================
// MOSTRAR SUBMENÚ
// =============================
$t_plugin = plugin_get();
$t_plugin->print_submenu('por_estado');
?>

<div class="col-md-12 col-xs-12">
    <div class="space-10"></div>

    <!-- Tabla de datos -->
    <div class="widget-box widget-color-blue2">
        <div class="widget-header widget-header-small">
            <h4 class="widget-title lighter">
                <i class="ace-icon fa fa-table"></i>
                Incidentes Creados vs Resueltos por Tipo de Prueba
            </h4>
        </div>
        <div class="widget-body">
            <div class="widget-main no-padding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tipo de Prueba</th>
                            <th>Total Creados</th>
                            <th>Total Resueltos</th>
                            <th>Pendientes</th>
                            <th>% Resueltos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($t_labels); $i++) {
                            $t_total = $t_data_total[$i];
                            $t_resueltos = $t_data_resueltos[$i];
                            $t_pendientes = $t_total - $t_resueltos;
                            $t_porcentaje = $t_total > 0 ? round(($t_resueltos / $t_total) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><?php echo string_display_line($t_labels[$i]) ?></td>
                                <td><?php echo $t_total ?></td>
                                <td><?php echo $t_resueltos ?></td>
                                <td><?php echo $t_pendientes ?></td>
                                <td><?php echo $t_porcentaje ?>%</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gráfico de barras comparativo -->
    <?php if (!empty($t_labels)) { ?>
        <div class="space-10"></div>
        <div class="widget-box widget-color-blue2">
            <div class="widget-header widget-header-small">
                <h4 class="widget-title lighter">
                    <i class="ace-icon fa fa-bar-chart"></i>
                    Gráfico Comparativo: Creados vs Resueltos
                </h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div style="position: relative; height: 400px;">
                        <canvas id="chartEstado"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script>
            const labels = <?php echo json_encode($t_labels) ?>;
            const dataTotal = <?php echo json_encode($t_data_total) ?>;
            const dataResueltos = <?php echo json_encode($t_data_resueltos) ?>;
            const porcentajes = <?php echo json_encode($t_porcentajes) ?>;

            // 🔹 Calcular el valor máximo + 1 para que no llegue al tope
            const maxValue = Math.max(...dataTotal) + 1;

            // 🔹 Calcular porcentaje global
            const totalGlobal = dataTotal.reduce((a, b) => a + b, 0);
            const resueltosGlobal = dataResueltos.reduce((a, b) => a + b, 0);
            const porcentajeGlobal = totalGlobal > 0 ? ((resueltosGlobal / totalGlobal) * 100).toFixed(1) : 0;

            const ctx = document.getElementById('chartEstado').getContext('2d');

            // Registrar el plugin
            Chart.register(ChartDataLabels);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Creados',
                            data: dataTotal,
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            categoryPercentage: 0.6,
                            barPercentage: 0.8
                        },
                        {
                            label: 'Total Resueltos',
                            data: dataResueltos,
                            backgroundColor: 'rgba(255, 205, 86, 0.8)',
                            borderColor: 'rgba(255, 205, 86, 1)',
                            borderWidth: 2,
                            categoryPercentage: 0.6,
                            barPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'top',
                            labels: {
                                font: { size: 12 }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Comparación: Incidentes Creados vs Resueltos por Tipo de Prueba',
                            font: { size: 16, weight: 'bold' }
                        },
                        datalabels: {
                            color: '#000',
                            font: { 
                                weight: 'bold', 
                                size: 12 
                            },
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value, context) {
                                // Mostrar porcentaje solo para las barras de resueltos
                                if (context.datasetIndex === 1) {
                                    return porcentajes[context.dataIndex] + '%';
                                }
                                return ''; // No mostrar nada en las barras totales
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                stepSize: 1,
                                font: { size: 11 }
                            },
                            title: { 
                                display: true, 
                                text: 'Cantidad de Incidentes',
                                font: { weight: 'bold' }
                            },
                            suggestedMax: maxValue,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: { 
                            title: { 
                                display: true, 
                                text: 'Tipo de Prueba',
                                font: { weight: 'bold' }
                            },
                            ticks: {
                                font: { size: 11 }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // 🔹 Mostrar porcentaje global debajo del gráfico
            const globalDiv = document.createElement('div');
            globalDiv.style.textAlign = 'center';
            globalDiv.style.marginTop = '15px';
            globalDiv.style.padding = '10px';
            globalDiv.style.backgroundColor = '#f8f9fa';
            globalDiv.style.borderRadius = '5px';
            globalDiv.style.fontWeight = 'bold';
            globalDiv.innerHTML = `
                <div style="font-size: 14px; color: #333;">
                    <span style="color: #36a2eb;">Total de Incidentes: ${totalGlobal}</span> | 
                    <span style="color: #ffcd56;">Resueltos: ${resueltosGlobal}</span> | 
                    <span style="color: #28a745;">Porcentaje Global de Resolución: ${porcentajeGlobal}%</span>
                </div>
            `;
            document.querySelector('#chartEstado').parentElement.appendChild(globalDiv);
        </script>
    <?php } ?>
</div>

<?php
layout_page_end();