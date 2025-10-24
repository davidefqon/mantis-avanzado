<?php  
auth_ensure_user_authenticated();  
form_security_validate('plugin_Mejoras_registrar');  
  
$f_titulo = gpc_get_string('titulo');  
$f_descripcion = gpc_get_string('descripcion');  
$f_tipo_mejora = gpc_get_string('tipo_mejora');  
$f_motivo = gpc_get_string('motivo', '');  
  
form_security_purge('plugin_Mejoras_registrar');  
  
// Validar campos requeridos  
if(empty($f_titulo) || empty($f_descripcion) || empty($f_tipo_mejora)) {  
    trigger_error(ERROR_EMPTY_FIELD, ERROR);  
}  
  
// Obtener información del usuario y proyecto actual  
$t_user_id = auth_get_current_user_id();  
$t_project_id = helper_get_current_project();  
  
// Insertar en la base de datos  
$t_mejora_table = plugin_table('mejora');  
  
db_param_push();  
$t_query = "INSERT INTO $t_mejora_table   
            (titulo, descripcion, tipo_mejora, motivo, user_id, project_id, date_submitted)  
            VALUES (" . db_param() . ", " . db_param() . ", " . db_param() . ", "   
            . db_param() . ", " . db_param() . ", " . db_param() . ", " . db_param() . ")";  
  
$t_result = db_query(  
    $t_query,   
    array(  
        $f_titulo,   
        $f_descripcion,   
        $f_tipo_mejora,   
        $f_motivo,  
        $t_user_id,  
        $t_project_id,  
        db_now()  
    )  
);  
  
$t_mejora_id = db_insert_id($t_mejora_table);  
  
// Redirigir a la página de éxito  
print_header_redirect(plugin_page('ver', true) . '&id=' . $t_mejora_id);