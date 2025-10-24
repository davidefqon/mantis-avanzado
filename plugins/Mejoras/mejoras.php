<?php  
class MejorasPlugin extends MantisPlugin {  
    function register() {  
        $this->name = 'Registro de Mejoras';  
        $this->description = 'Plugin para registrar mejoras del sistema';  
        $this->page = 'registrar';  
          
        $this->version = '1.0';  
        $this->requires = array(  
            'MantisCore' => '2.0',  
        );  
          
        $this->author = 'David';  
        $this->contact = 'tu@email.com';  
        $this->url = '';  
    }  
      
    function schema() {  
        return array(  
            array('CreateTableSQL', array(plugin_table('mejora'), "  
                id              I       UNSIGNED NOTNULL PRIMARY AUTOINCREMENT,  
                titulo          C(128)  NOTNULL DEFAULT '',  
                descripcion     X       NOTNULL,  
                tipo_mejora     C(50)   NOTNULL DEFAULT '',  
                motivo          X       NULL,  
                user_id         I       UNSIGNED NOTNULL DEFAULT '0',  
                project_id      I       UNSIGNED NOTNULL DEFAULT '0',  
                date_submitted  I       UNSIGNED NOTNULL DEFAULT '1'  
            ", array('mysql' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8', 'pgsql' => 'WITHOUT OIDS'))),  
              
            array('CreateIndexSQL', array('idx_mejora_user', plugin_table('mejora'), 'user_id')),  
            array('CreateIndexSQL', array('idx_mejora_project', plugin_table('mejora'), 'project_id')),  
        );  
    }  
      
    function hooks() {  
        return array(  
            'EVENT_MENU_MAIN' => 'menu',  
        );    
    }  
      
    function menu() {  
        return array(  
            array(  
                'title' => 'Registrar Mejora',  
                'url' => plugin_page('registrar'),  
                'access_level' => REPORTER,  
                'icon' => 'fa-lightbulb-o'  
            ),  
            array(  
                'title' => 'Ver Mejoras',  
                'url' => plugin_page('listar'),  
                'access_level' => REPORTER,  
                'icon' => 'fa-list'  
            )  
        );  
    }  

}