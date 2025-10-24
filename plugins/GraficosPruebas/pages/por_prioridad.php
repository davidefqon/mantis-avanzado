<?php  
auth_ensure_user_authenticated();  

layout_page_header('Gráficos por Prioridad');  
layout_page_begin();  

// Obtener proyecto actual  
$t_project_id = helper_get_current_project();  
$t_bug_table = db_get_table('bug');  

// Manejar caso "todos los proyectos"
if( $t_project_id == ALL_PROJECTS ) {
    $t_where = '1=1';
    $t_params = array();
} else {
    $t_where = 'project_id = ' . db_param();
    $t_params = array($t_project_id);
}

// Consulta de prioridades
db_param_push();  
$t_query = "SELECT priority, COUNT(*) as count  
            FROM $t_bug_table  
            WHERE $t_where  
            GROUP BY priority  
            ORDER BY priority ASC";  

$t_result = db_query($t_query, $t_params);  

$t_labels = array();  
$t_data = array();  

while($t_row = db_fetch_array($t_result)) {  
    $t_labels[] = get_enum_element('priority', $t_row['priority']);  
    $t_data[] = (int)$t_row['count'];  
}  

$t_plugin->print_submenu('por_prioridad');
?>  

<div class="col-md-6 col-xs-12">  
<div class="space-10"></div>  

<div class="widget-box widget-color-blue2">  
    <div class="widget-header widget-header-small">  
        <h4 class="widget-title lighter">  
            <i class="ace-icon fa fa-exclamation-triangle"></i>  
            Gráfico por Prioridad  
        </h4>  
    </div>  

    <div class="widget-body">  
        <div class="widget-main center">
            <div style="height: 300px;">
                <canvas id="chartPrioridad"></canvas>
            </div>
        </div>  
    </div>  
</div>  
</div>  

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>  
<script>  
new Chart(document.getElementById('chartPrioridad'), {  
    type: 'bar',  
    data: {  
        labels: <?php echo json_encode($t_labels) ?>,  
        datasets: [{  
            label: 'Cantidad de Casos',  
            data: <?php echo json_encode($t_data) ?>,  
            backgroundColor: 'rgba(255, 206, 86, 0.6)',  
            borderColor: 'rgba(255, 206, 86, 1)',  
            borderWidth: 2  
        }]  
    },  
    options: {  
        responsive: true,  
        maintainAspectRatio: false,  
        plugins: {  
            legend: {  
                display: true,  
                position: 'top'  
            }  
        },  
        scales: {  
            y: {  
                beginAtZero: true,  
                ticks: { stepSize: 1 }  
            }  
        }  
    }  
});  
</script>  

<?php layout_page_end(); ?>
