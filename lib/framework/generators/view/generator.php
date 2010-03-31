<?php
class ViewGenerator extends Generator {
  public $option_mappings = array(0 => 'controller_name'
                                 ,1 => 'view_name'
                                 ,2 => 'view_format');
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    
    $this->view_path           = $this->app_root.'/application/views/'.\Inflector::underscore($this->controller_name);
    //$this->view_format         = 
    //$this->controller_path     = $this->app_root.'/application/controllers';
    //$this->controller_filename = Inflector::underscore($this->class_name).'_controller.php';

    $this->directory($this->view_path);
    
    $this->template($this->template_root.'/view.'.$this->view_format.'.php',
                    $this->view_path.'/'.$this->view_name.'.'.$this->view_format.'.php',
                    array('controller_name' => $this->controller_name
                         ,'view_name'       => $this->view_name
                         ,'view_format'     => $this->view_format));
  }
}
?>