<?php  
require_once( 'core.php' );  
require_api( 'access_api.php' );  
require_api( 'authentication_api.php' );  
require_api( 'config_api.php' );  
require_api( 'helper_api.php' );  
require_api( 'project_api.php' );  
require_api( 'layout_api.php' );  
  
// Autenticar usuario  
auth_ensure_user_authenticated();  
  
// Obtener el proyecto actual  
$t_project_id = helper_get_current_project();  
  
// Obtener el nombre del proyecto  
$t_project_name = project_get_name($t_project_id);  
  
// Guardar en archivo txt  
$t_filename = 'proyecto_actual.txt';  
$t_content = "Proyecto ID: " . $t_project_id . "\n";  
$t_content .= "Nombre: " . $t_project_name . "\n";  
$t_content .= "Fecha: " . date('Y-m-d H:i:s') . "\n";  
file_put_contents($t_filename, $t_content);  
  
// Renderizar la página  
layout_page_header();  
layout_page_begin();  
?>  
  
<div class="col-md-12 col-xs-12">  
    <div class="widget-box widget-color-blue2">  
        <div class="widget-header widget-header-small">  
            <h4 class="widget-title lighter">  
                Información del Proyecto  
            </h4>  
        </div>  
        <div class="widget-body">  
            <div class="widget-main no-padding">  
                <p>Proyecto guardado: <?php echo $t_project_name ?></p>  
            </div>  
        </div>  
    </div>  
</div>  
  
<?php  
layout_page_end();  
?>