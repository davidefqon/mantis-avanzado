<?php  
auth_ensure_user_authenticated();  
  
layout_page_header(plugin_lang_get('title'));  
layout_page_begin();  
  
$t_user_id = auth_get_current_user_id();  
$t_project_id = helper_get_current_project();  
$t_mejora_table = plugin_table('mejora');  
  
// Consultar mejoras del proyecto actual  
db_param_push();  
$t_query = "SELECT * FROM $t_mejora_table   
            WHERE project_id = " . db_param() . "  
            ORDER BY date_submitted DESC";  
$t_result = db_query($t_query, array($t_project_id));  
?>  
  
<div class="col-md-12 col-xs-12">  
<div class="space-10"></div>  
  
<div class="widget-box widget-color-blue2">  
    <div class="widget-header widget-header-small">  
        <h4 class="widget-title lighter">  
            <?php print_icon('fa-list', 'ace-icon'); ?>  
            <?php echo plugin_lang_get('lista_mejoras') ?>  kbhjbhjbjh 
        </h4>  
    </div>  
      
    <div class="widget-body">  
        <div class="widget-main no-padding">  
            <div class="table-responsive">  
                <table class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  
                            <th>ID</th>  
                            <th><?php echo plugin_lang_get('titulo') ?></th>  
                            <th><?php echo plugin_lang_get('tipo_mejora') ?></th>  
                            <th>Usuario</th>  
                            <th>Fecha</th>  
                            <th>Acciones</th>  
                        </tr>  
                    </thead>  
                    <tbody>  
                        <?php  
                        while($t_row = db_fetch_array($t_result)) {  
                            $t_user_name = user_get_name($t_row['user_id']);  
                            $t_date = date('Y-m-d H:i', $t_row['date_submitted']);  
                        ?>  
                        <tr>  
                            <td><?php echo $t_row['id'] ?></td>  
                            <td><?php echo string_display_line($t_row['titulo']) ?></td>  
                            <td><?php echo string_display_line($t_row['tipo_mejora']) ?></td>  
                            <td><?php echo $t_user_name ?></td>  
                            <td><?php echo $t_date ?></td>  
                            <td>  
                                <a href="<?php echo plugin_page('ver') . '&id=' . $t_row['id'] ?>"   
                                   class="btn btn-xs btn-primary">  
                                    Ver  
                                </a>  
                            </td>  
                        </tr>  
                        <?php } ?>  
                    </tbody>  
                </table>  
            </div>  
        </div>  
    </div>  
</div>  
  
<p>  
    <a href="<?php echo plugin_page('registrar') ?>" class="btn btn-primary">  
        <?php echo plugin_lang_get('registrar_mejora') ?>  
    </a>  
</p>  
  
</div>  
  
<?php  
layout_page_end();