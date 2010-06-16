<?php
namespace Generator;
class Scaffold extends Base {
  public $option_mappings = array(0 => 'model_name'); //CamelCased
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();

    $this->model_path          = $this->app_root.'/application/models';
    $this->model_filename      = \Inflector\Base::underscore($this->model_name).'.php';
    $this->model_class         = $this->model_name;
    $this->us_model_class      = \Inflector\Base::underscore($this->model_class);
    $this->pl_model_class      = \Inflector\Base::pluralize($this->us_model_class);
    
    $this->test_path           = $this->app_root.'/tests/models';
    $this->test_filename       = \Inflector\Base::underscore($this->model_name).'_test.php';

    $this->controller_class    = $this->model_class;
    $this->us_controller_class = \Inflector\Base::underscore($this->controller_class);
    $this->controller_path     = $this->app_root.'/application/controllers';
    $this->controller_filename = \Inflector\Base::underscore($this->controller_class).'_controller.php';
    
    //$this->helper_name         = \Inflector\Base::underscore($this->model_name);
    $this->helper_path         = $this->app_root.'/application/helpers';    
    $this->helper_filename     = \Inflector\Base::underscore($this->us_controller_class).'_helpers.php';
    
    $this->view_path           = $this->app_root.'/application/views/'.\Inflector\Base::underscore($this->us_controller_class);
    
    $this->template($this->template_root.'/model.php',
                    $this->model_path.'/'.$this->model_filename,
                    array('model_class' => $this->model_class));
                    
    $this->template($this->template_root.'/model_test.php',
                    $this->test_path.'/'.$this->test_filename,
                    array('model_class' => $this->model_class));
                    
    $this->template($this->template_root.'/controller.php',
                    $this->controller_path.'/'.$this->controller_filename,
                    array('controller_class'    => $this->controller_class
                         ,'us_controller_class' => $this->us_controller_class
                         ,'model_class'         => $this->model_class
                         ,'us_model_class'      => $this->us_model_class
                         ,'pl_model_class'      => $this->pl_model_class));
                         
    $this->template($this->template_root.'/helpers.php',
                    $this->helper_path.'/'.$this->helper_filename,
                    array('controller_class'    => $this->controller_class
                         ,'us_controller_class' => $this->us_controller_class
                         ,'model_class'         => $this->model_class
                         ,'us_model_class'      => $this->us_model_class));
    

    $this->directory($this->view_path);
    
    $this->template(
      $this->template_root.'/views/_form.xhtml.php'
     ,$this->view_path.'/_form.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));
    
    $this->template(
      $this->template_root.'/views/add.xhtml.php'
     ,$this->view_path.'/add.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));
/*    
    $this->template(
      $this->template_root.'/views/user/create.xhtml.php'
     ,$this->view_path.'/create.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));
*/    
    $this->template(
      $this->template_root.'/views/edit.xhtml.php'
     ,$this->view_path.'/edit.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));
    
    $this->template(
      $this->template_root.'/views/enlist.xhtml.php'
     ,$this->view_path.'/enlist.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class
           ,'pl_model_class'      => $this->pl_model_class));
/*    
    $this->template(
      $this->template_root.'/views/update.xhtml.php'
     ,$this->view_path.'update.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));
    */
    $this->template(
      $this->template_root.'/views/view.xhtml.php'
     ,$this->view_path.'/view.xhtml.php'
     ,array('controller_class'    => $this->controller_class
           ,'us_controller_class' => $this->us_controller_class
           ,'model_class'         => $this->model_class
           ,'us_model_class'      => $this->us_model_class));             
  }
}
?>