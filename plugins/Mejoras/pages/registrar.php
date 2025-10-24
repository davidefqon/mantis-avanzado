<?php  
layout_page_header(plugin_lang_get('title'));  
layout_page_begin();  
?>  
  
<div class="col-md-12 col-xs-12">  
<div class="space-10"></div>  
  
<div class="form-container">  
    <form action="<?php echo plugin_page('registrar_action') ?>" method="post">  
        <?php echo form_security_field('plugin_Mejoras_registrar') ?>  
          
        <div class="widget-box widget-color-blue2">  
            <div class="widget-header widget-header-small">  
                <h4 class="widget-title lighter">  
                    <?php print_icon('fa-lightbulb-o', 'ace-icon'); ?>  
                    <?php echo plugin_lang_get('title') ?>  
                </h4>  
            </div>  
              
            <div class="widget-body">  
                <div class="widget-main no-padding">  
                    <div class="table-responsive">  
                        <table class="table table-bordered table-condensed">  
                            <tr>  
                                <th class="category" width="30%">  
                                    <label for="titulo">  
                                        <?php echo plugin_lang_get('titulo') ?>  
                                        <span class="required">*</span>  
                                    </label>  
                                </th>  
                                <td width="70%">  
                                    <input type="text"   
                                           id="titulo"   
                                           name="titulo"   
                                           class="input-sm"   
                                           size="80"   
                                           maxlength="128"   
                                           required />  
                                </td>  
                            </tr>  
                              
                            <tr>  
                                <th class="category">  
                                    <label for="descripcion">  
                                        <?php echo plugin_lang_get('descripcion') ?>  
                                        <span class="required">*</span>  
                                    </label>  
                                </th>  
                                <td>  
                                    <textarea id="descripcion"   
                                              name="descripcion"   
                                              class="form-control"   
                                              rows="8"   
                                              required></textarea>  
                                </td>  
                            </tr>  
                              
                            <tr>  
                                <th class="category">  
                                    <label for="tipo_mejora">  
                                        <?php echo plugin_lang_get('tipo_mejora') ?>  
                                        <span class="required">*</span>  
                                    </label>  
                                </th>  
                                <td>  
                                    <select id="tipo_mejora" name="tipo_mejora" class="input-sm" required>  
                                        <option value="">-- Seleccione --</option>  
                                        <option value="Funcional">Funcional</option>  
                                        <option value="Interfaz">Interfaz</option>  
                                        <option value="Rendimiento">Rendimiento</option>  
                                        <option value="Seguridad">Seguridad</option>  
                                        <option value="Usabilidad">Usabilidad</option>  
                                        <option value="Proceso interno">Proceso interno</option>  
                                    </select>  
                                </td>  
                            </tr>  
                              
                            <tr>  
                                <th class="category">  
                                    <label for="motivo">  
                                        <?php echo plugin_lang_get('motivo') ?>  
                                    </label>  
                                </th>  
                                <td>  
                                    <textarea id="motivo"   
                                              name="motivo"   
                                              class="form-control"   
                                              rows="5"></textarea>  
                                </td>  
                            </tr>  
                        </table>  
                    </div>  
                </div>  
                  
                <div class="widget-toolbox padding-8 clearfix">  
                    <button type="submit" class="btn btn-primary btn-white btn-round">  
                        <?php echo plugin_lang_get('registrar_mejora') ?>  
                    </button>  
                </div>  
            </div>  
        </div>  
    </form>  
</div>  
</div>  
  
<?php  
layout_page_end();