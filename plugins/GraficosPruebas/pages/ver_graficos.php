<?php
auth_ensure_user_authenticated();

layout_page_header(plugin_lang_get('title'));
layout_page_begin();

// Tu código de consulta aquí...  
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

db_param_push();
$t_query = "SELECT cfs.value, COUNT(*) as count  
            FROM $t_custom_field_string_table cfs  
            INNER JOIN $t_bug_table b ON cfs.bug_id = b.id  
            WHERE cfs.field_id = " . db_param() . "  
            AND b.project_id = " . db_param() . "  
            GROUP BY cfs.value  
            ORDER BY count DESC";

$t_result = db_query($t_query, array($t_field_id, $t_project_id));

$t_labels = array();
$t_data = array();

while ($t_row = db_fetch_array($t_result)) {
    $t_labels[] = $t_row['value'];
    $t_data[] = (int) $t_row['count'];
}

// Calcular porcentajes
$t_total = array_sum($t_data);
$t_percentages = array();
foreach ($t_data as $count) {
    $t_percentages[] = $t_total > 0 ? round(($count / $t_total) * 100, 1) : 0;
}

$t_plugin->print_submenu('ver_graficos');
?>

<div class="col-md-6 col-xs-12">
    <div class="space-10"></div>

    <div class="widget-box widget-color-blue2">
        <div class="widget-header widget-header-small">
            <h4 class="widget-title lighter">
                <i class="ace-icon fa fa-bar-chart"></i>
                Distribución por Tipo de Prueba
            </h4>
        </div>

        <div class="widget-body">
            <div class="widget-main center">
                <div style="height: 300px; margin: 0 auto;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    

    

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    var percentages = <?php echo json_encode($t_percentages); ?>;
    var data = <?php echo json_encode($t_data); ?>;
    var maxValue = Math.max(...data);

    new Chart(document.getElementById('myChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($t_labels) ?>,
            datasets: [
                {
                    type: 'bar',
                    label: 'Cantidad',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    barThickness: 20,
                    datalabels: {
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        formatter: function (value, context) {
                            return percentages[context.dataIndex] + '%';
                        },
                        font: {
                            weight: 'bold',
                            size: 11
                        }
                    }
                },
                {
                    type: 'line',
                    label: 'Línea de tendencia',
                    data: data,
                    borderColor: 'rgba(255, 0, 0, 0.8)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0,

                    pointBackgroundColor: 'rgba(255, 0, 0, 1)',
                    pointRadius: 4,
                    pointStyle: 'cross'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 30  // Espacio adicional en la parte superior para las etiquetas
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                datalabels: {
                    display: true,
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    offset: 8  // Ajusta la distancia desde la barra
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    // Aumentar el límite máximo del eje Y
                    suggestedMax: maxValue + 1,
                    grid: {
                        drawBorder: true
                    }
                },
                x: {
                    ticks: {
                        font: { size: 10 }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });  
</script>

<?php
layout_page_end();