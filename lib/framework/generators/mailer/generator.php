<?php
namespace Generator;
use Inflector\Base as Inflector;
class Mailer extends Base {
  public $option_mappings = array(0 => 'class_name');
  
  public function commands() {
    $this->class_name          = $this->class_name.'Mailer';
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->model_path          = $this->app_root.'/application/models';
    $this->mailer_filename     = Inflector::underscore($this->class_name).'.php';
    $this->test_path           = $this->app_root.'/tests/models';
    $this->test_filename       = Inflector::underscore($this->class_name).'_test.php';

    $this->template($this->template_root.'/mailer.php',
                    $this->model_path.'/'.$this->mailer_filename,
                    array('class_name' => $this->class_name));
                    
    $this->template($this->template_root.'/mailer_test.php',
                    $this->test_path.'/'.$this->test_filename,
                    array('class_name' => $this->class_name));
  }
}
?>