<?php  
class ExamplePlugin extends MantisPlugin {  
    function register() {  
        $this->name = 'Example Plugin de david';  
        $this->description = 'Example plugin siguiendo la guia de desarrolladores';  
        $this->page = 'config';  
          
        $this->version = '2.0';  
        $this->requires = array(  
            'MantisCore' => '2.0',  
        );  
          
        $this->author = 'Andes QA David';  
        $this->contact = 'davidlarotapilco@gmail.com';  
        $this->url = 'https://losandes.pe';  
    }  
      
    function config() {  
        return array(  
            'foo_or_bar' => 'foo',  
        );  
    }  
      
    function events() {  
        return array(  
            'EVENT_EXAMPLE_FOO' => EVENT_TYPE_EXECUTE,  
            'EVENT_EXAMPLE_BAR' => EVENT_TYPE_CHAIN,  
        );  
    }  
      
    function hooks() {  
        return array(  
            'EVENT_MENU_MAIN' => 'menu',  
            'EVENT_EXAMPLE_FOO' => 'foo',  
            'EVENT_EXAMPLE_BAR' => 'bar',  
        );  
    }  
      
    function menu() {  
        return array(  
            'title' => $this->name,  
            'url' => plugin_page('foo'),  
            'access_level' => ANYBODY,  
            'icon' => 'fa-smile-o'  
        );  
    }  
      
    function foo($p_event) {  
        echo 'In method foo(). ';  
    }  
      
    function bar($p_event, $p_chained_param) {  
        return str_replace('foo', 'bar', $p_chained_param);  
    }  
}