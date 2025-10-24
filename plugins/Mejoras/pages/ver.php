<?php  
auth_ensure_user_authenticated();  
  
$f_mejora_id = gpc_get_int('id');  
$t_mejora_table = plugin_table('mejora');  
  
// Consultar la mejora  
db_param_push();  
$t_query = "SELECT * FROM $t_mejora_table WHERE id = " . db_param();  
$t_result = db_query($t_query, array($f_mejora_id));  
  
if(db_num_rows($t_result) == 0) {  
    trigger_error(ERROR_GENERIC, ERROR);  
}  
  
$t_mejora = db_fetch_array($t_result);  
  
layout_page_header(plugin_lang_get('title'));  
layout_page_begin();  
?>  
  
<div class="col-md-12 col-xs-12">  
<div class="space-10"></div>  
  
<div class="widget-box widget-color-blue2">  
    <div class="widget-header widget-header-small">  
        <h4 class="widget-title lighter">  
            <?php print_icon('fa-lightbulb-o', 'ace-icon'); ?>  
            Mejora #<?php echo $t_mejora['id'] ?>  
        </h4>  
    </div>  
      
    <div class="widget-body">  
        <div class="widget-main">  
            <table class="table table-bordered">  
                <tr>  
                    <th width="20%"><?php echo plugin_lang_get('titulo') ?></th>  
                    <td><?php echo string_display_line($t_mejora['titulo']) ?></td>  
                </tr>  
                <tr>  
                    <th><?php echo plugin_lang_get('tipo_mejora') ?></th>  
                    <td><?php echo string_display_line($t_mejora['tipo_mejora']) ?></td>  
                </tr>  
                <tr>  
                    <th><?php echo plugin_lang_get('descripcion') ?></th>  
                    <td><?php echo string_display_links($t_mejora['descripcion']) ?></td>  
                </tr>  
                <?php if(!empty($t_mejora['motivo'])) { ?>  
                <tr>  
                    <th><?php echo plugin_lang_get('motivo') ?></th>  
                    <td><?php echo string_display_links($t_mejora['motivo']) ?></td>  
                </tr>  
                <?php } ?>  
                <tr>  
                    <th>Usuario</th>  
                    <td><?php echo user_get_name($t_mejora['user_id']) ?></td>  
                </tr>  
                <tr>  
                    <th>Fecha</th>  
                    <td><?php echo date('Y-m-d H:i:s', $t_mejora['date_submitted']) ?></td>  
                </tr>  
            </table>  
        </div>  
    </div>  
</div>  
  
<p>  
    <a href="<?php echo plugin_page('listar') ?>" class="btn btn-default">  
        Volver a la lista  
    </a>  
</p>  
  
</div>  
  
<?php  
layout_page_end();