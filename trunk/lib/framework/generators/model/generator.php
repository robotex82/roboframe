<?php
class ModelGenerator extends Generator {
  public $option_mappings = array(0 => 'class_name');
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->model_path          = $this->app_root.'/application/models';
    $this->model_filename      = Inflector::underscore($this->class_name).'.php';
    $this->test_path           = $this->app_root.'/tests/models';
    $this->test_filename       = Inflector::underscore($this->class_name).'_test.php';

    $this->template($this->template_root.'/model.php',
                    $this->model_path.'/'.$this->model_filename,
                    array('class_name' => $this->class_name));
                    
    $this->template($this->template_root.'/model_test.php',
                    $this->test_path.'/'.$this->test_filename,
                    array('class_name' => $this->class_name));
  }
}
?>