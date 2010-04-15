<?php
class ControllerGenerator extends Generator {
  public $option_mappings = array(0 => 'class_name'
                                 ,1 => 'methods');
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->helper_path         = $this->app_root.'/application/helpers';    
    $this->controller_path     = $this->app_root.'/application/controllers';
    $this->controller_filename = Inflector::underscore($this->class_name).'_controller.php';
    $this->helper_filename     = Inflector::underscore($this->class_name).'_helpers.php';

    $this->template($this->template_root.'/controller.php',
                    $this->controller_path.'/'.$this->controller_filename,
                    array('class_name' => $this->class_name
                         ,'methods'    => $this->methods));
                         
    $this->template($this->template_root.'/helpers.php',
                    $this->helper_path.'/'.$this->helper_filename,
                    array('class_name' => $this->class_name));
  }
}
?>