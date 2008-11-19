<?php
require_once('class.OutputManager.php');
class View {
  //protected $output_format = 'xhtml';
  protected $view_data = array();
  protected $output_manager = null;
  protected $output_format;
  //protected $layout = 'default';
  protected $layout_path;
  protected $view_path;
  protected $controller_name;
  protected $action_name;  
  
  public function __construct($controller_name, $action_name, $view_data, $output_format, $layout) {
    //$this->set_view_path($view_path);
    $this->set_controller_name($controller_name);
    $this->set_action_name($action_name);
    $this->set_view_data($view_data);
    $this->set_output_format($output_format);
    $this->output_manager = OutputManager::get_output_manager_for($output_format);
    //$om->initialize();
    $this->output_manager->set_layout($layout);
    $this->set_layout_path($this->output_manager->get_layout_path());
  }
/*  
  public function set_view_path($view_path) {
    $this->view_path = $view_path;
  }
*/  
  public function get_view_path() {
    $output_format = $this->get_output_format();
    $output_parts = explode(':', $output_format);
    $output_extension = $output_parts[0];
    return VIEW_ROOT . '/' . $this->get_controller_name() . '/' . $this->get_action_name() . '.'.$output_extension.'.php';
    //return $this->view_path;
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
  
  public function render() {
    require_once(FRAMEWORK_PATH.'/helpers/xhtml.php');
    require_once(APPLICATION_ROOT.'/helpers/application.php');    
    //$view = VIEW_ROOT . "/" . $this->getName() . "/" . $action . ".php";
    $content = $this->get_view_path();
    $layout = $this->get_layout_path();

    //if (!is_file($view)) {
    if (!is_file($content)) {
      //exit("View [".$view."] not found");
      exit('View ['.$content.'] not found');
    }

    if(method_exists($this->output_manager, 'before_render')) {
      $this->output_manager->before_render($this);
    }

    //Create variables for the template
    foreach ($this->get_view_data() as $key => $value) {
      $$key = $value;
    }
   
    // support for templates
    if(is_file($layout)) {
      include($layout);
    } else {
      include($content);
    }
    
    if(method_exists($this->output_manager, 'after_render')) {
      $this->output_manager->after_render($this);
    }  
  }
}
?>