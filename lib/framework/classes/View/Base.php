<?php
namespace View;
// TODO: Change this!

use Exception;
\Roboframe\Base::enable_module('Output\Manager\Base');
\Roboframe\Base::enable_module('Html\Form');
class Base {
  //protected $output_format = 'xhtml';
  protected $view_data = array();
  protected $output_manager = null;
  protected $output_format;
  //protected $layout = 'default';
  protected $layout_path;
  protected $view_path;
  static protected $_view_root = false;
  static protected $_layout_root = false;
  protected $controller_name;
  protected $action_name;  
  static protected $helpers = array();
  
  static function register_helper($helper_path) {
  	if(!is_readable($helper_path)) {
      \Logger\Base::logger()->warn("Could not register helper. Can't read [$helper_path]");		
  	  //throw new Exception("Could not register helper. Can't read [$helper_path]");
  	  return false;
  	}
  	array_push(self::$helpers, $helper_path);
  }
  
  static function registered_helpers() {
  	return self::$helpers;
  	
  }
  
  public function __construct($controller_name, $action_name, $view_data, $output_format, $layout) {
    \Output\Manager\Base::auto_register_framework_output_handlers();
    //$this->set_view_root(VIEW_ROOT);
    //$this->set_view_path($view_path);
    $this->set_controller_name($controller_name);
    $this->set_action_name($action_name);
    //var_dump($view_data);
    $this->set_view_data($view_data);
    $this->set_output_format($output_format);
    $this->output_manager = \Output\Manager\Base::get_output_manager_for($output_format);
    //$om->initialize();
    $this->output_manager->set_layout($layout);
    $this->set_layout_path($this->output_manager->get_layout_path());
    
    //self::register_helper(FRAMEWORK_PATH.'/helpers/xhtml.php');
    self::register_helper(APPLICATION_ROOT.'/helpers/application_helpers.php');
  }
/*  
  public function set_view_path($view_path) {
    $this->view_path = $view_path;
  }
*/  
  public function get_view_path() {
    $output_format = $this->get_output_format();
    $output_parts = explode('|', $output_format);
    $output_extension = $output_parts[0];
//    return VIEW_ROOT . '/' . $this->get_controller_name() . '/' . $this->get_action_name() . '.'.$output_extension.'.php';
    return self::view_root() . '/' . $this->get_controller_name() . '/' . $this->get_action_name() . '.'.$output_extension.'.php';
    //return $this->view_path;
  }
  
  public static function set_view_root($view_root) {
    self::$_view_root = $view_root;
  }
  
  public static function view_root() {
    return self::$_view_root;
  }
  
  public static function set_layout_root($lr) {
    self::$_layout_root = $lr;
  }
  
  public static function layout_root() {
    return self::$_layout_root;
  }
  
  public function add_view_data($key, $data) {
    $this->view_data[$key] = $data;
  }
  
  public function set_view_data($view_data) {
    $this->view_data = $view_data;
  }
  
  public function get_view_data() {
    return $this->view_data;
  }
  
  public function get_view_item($key) {
    return $this->view_data[$key];
  }

  public function get_layout() {
    return $this->get_layout;
  }

  public function get_layout_path() {
    return $this->layout_path;
  }
  
  public function set_layout_path($layout_path) {
    $this->layout_path = $layout_path;
  }
 
  public function set_layout($layout) {
    $this->layout = $layout;
  }
  
  public function set_output_format($output_format) {
    $this->output_format = $output_format;  
  }
  
  public function get_output_format() {
    return $this->output_format;
  }
  
  public function get_controller_name() {
    return $this->controller_name;
  }
  
  public function set_controller_name($controller_name) {
    $this->controller_name = $controller_name;
  }  
  
  public function get_action_name() {
    return $this->action_name;
  }
  
  public function set_action_name($action_name) {
    $this->action_name = $action_name;
  } 
  
  public function render($return_content = false) {
  	/*
    require_once(FRAMEWORK_PATH.'/helpers/xhtml.php');
    require_once(APPLICATION_ROOT.'/helpers/application_helpers.php'); 
    $controller_helper = APPLICATION_ROOT.'/helpers/'.$this->get_controller_name().'_helpers.php';
    if(is_readable($controller_helper)) {
      require_once($controller_helper);
    } 
    */  

  	self::register_helper(APPLICATION_ROOT.'/helpers/'.$this->get_controller_name().'_helpers.php');
  	
  	foreach(self::registered_helpers() as $h) {
  		require_once($h);
  	}
  	
    //$view = VIEW_ROOT . "/" . $this->getName() . "/" . $action . ".php";
    $content = $this->get_view_path();
    //$content = $this->get_view_root().$this->get_view_path();
    $layout = $this->get_layout_path();

    //if (!is_file($view)) {
    //if (!is_file($content)) {
    if ($this->output_manager->render_view() && !is_file($content)) {
      //exit("View [".$view."] not found");
      exit('View ['.$content.'] not found in ['.self::view_root().']');
    }

    if(method_exists($this->output_manager, 'before_render')) {
      $this->output_manager->before_render($this);
    }
    
    if($this->output_manager->render_view()) {
      //Create variables for the template
      foreach ($this->get_view_data() as $key => $value) {
        $$key = $value;
      }

      ob_start();
      // support for templates
      if(is_file($layout)) {
        include($layout);
      } else {
        include($content);
      }
      if($return_content) {
        $rendered_content = ob_get_clean();
      } else {
        ob_end_flush();
      }   
    }

    if(method_exists($this->output_manager, 'after_render')) {
      $this->output_manager->after_render($this);
    } 
    
    if($return_content) {
      return $rendered_content;
    } 
  }
  
  public function render_partial($name) {
    /*
    var_dump(debug_backtrace());
    $args = func_get_args();
    $name = $args[0];
    $local_vars = array();
    if(func_num_args() > 0) {
      for($i = 1; $i < func_num_args(); $i++) {
        $local_vars[] = $args[$i];
      }
    } 
    */ 

    if(strstr($name, '/')) {
      $file =  self::view_root() . '/' . $name . ".php";
    } else {
      $file = self::view_root() . '/' . $this->get_controller_name() . '/_' . $name . ".php";
    }

    if(!file_exists($file)) {
      throw new \Exception("Partial [{$file}] does not exist!");
    }
    
    if(!is_readable($file)) {
      throw new \Exception("Could not read partial [{$file}]!");
    }
    /*
    foreach($local_vars as $var => $value) {
      $$var = $value;
    }
    */
    //Create variables for the template
    foreach ($this->get_view_data() as $key => $value) {
      $$key = $value;
    }    
    include($file);
    
  }
}