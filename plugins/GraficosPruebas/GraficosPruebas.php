<?php  
class GraficosPruebasPlugin extends MantisPlugin {  
    function register() {  
        $this->name = 'Gráficos de Pruebas';  
        $this->description = 'Plugin para visualizar estadísticas de tipos de prueba';  
        $this->page = 'ver_graficos';  
          
        $this->version = '1.0';  
        $this->requires = array(  
            'MantisCore' => '2.0',  
        );  
          
        $this->author = 'David';  
        $this->contact = 'brahyanarch@email.com';  
        $this->url = '';  
    }  
      
    function hooks() {  
        return array(  
            'EVENT_MENU_MAIN' => 'menu',  
            'EVENT_CORE_HEADERS' => 'csp_headers',  
        );  
    }  
      
    function menu() {  
        return array(  
            array(  
                'title' => 'Gráficos Avanzados',  
                'url' => plugin_page('ver_graficos'),  
                'access_level' => REPORTER,  
                'icon' => 'fa-bar-chart'  
            )  
        );  
    }  
      
    function csp_headers() {  
        http_csp_add('script-src', 'https://cdn.jsdelivr.net');  
        http_csp_add('script-src', "'unsafe-inline'");  
    }  
      
    // NUEVA FUNCIÓN: Generar el menú de navegación  
    function print_submenu($p_current_page = ''): void {  
        $t_pages = array(  
            'ver_graficos' => array(  
                'url' => plugin_page('ver_graficos'),  
                'label' => 'Por Tipo de Prueba',  
                'icon' => 'fa-bar-chart'  
            ),  
            'por_hallazgo' => array(  
                'url' => plugin_page('por_hallazgo'),  
                'label' => 'Por Tipo de Hallazgo',  
                'icon' => 'fa-bug'  
            ),  
            'por_prioridad' => array(  
                'url' => plugin_page('por_prioridad'),  
                'label' => 'Por Prioridad',  
                'icon' => 'fa-exclamation-triangle'  
            ),  
            'por_estado' => array(  
                'url' => plugin_page('por_estado'),  
                'label' => 'Por Estado',  
                'icon' => 'fa-tasks'  
            )  
        );  
          
        echo '<div class="space-10"></div>' . "\n";  
        echo '<div class="center">' . "\n";  
        echo '<div class="btn-toolbar inline">' . "\n";  
        echo '<div class="btn-group">' . "\n";  
          
        foreach($t_pages as $t_page_key => $t_page) {  
            $t_active = ($t_page_key == $p_current_page) ? 'active' : '';  
            echo '<a class="btn btn-sm btn-white btn-primary ' . $t_active . '" href="' . $t_page['url'] . '">';  
            echo '<i class="ace-icon ' . $t_page['icon'] . '"></i> ';  
            echo $t_page['label'];  
            echo '</a>' . "\n";  
        }  
          
        echo '</div>' . "\n";  
        echo '</div>' . "\n";  
        echo '</div>' . "\n";  
    }  
}