<?php
namespace Controller;
//require_once('classes/class.View.php');
abstract class Action extends Base {
  protected $_name;
  protected $viewData      = array();
  public    $layout; //        = 'default';
  public    $output_format = 'xhtml';
  protected $flash;
  
  public function flash() {
    return $this->flash;
  }
  
  public function __construct() {
    parent::__construct();
    $this->flash = new \Flash\Base();
  }
  
  /* Alias for set_output_format() */
  public function render_as($output_format) {
    $this->set_output_format($output_format);
  }
  
  public function set_output_format($output_format) {
    $this->output_format = $output_format;
  }
  
  public function get_output_format() {
    return $this->output_format;
  }
  
  public function setName($name) {
    $this->_name = $name;
  }
  public function getName() {
    return $this->_name;
  }
  
  public function setVar($key, $value) {
    $this->viewData[$key] = $value;
  }
  public function getVar($key) {
    if (array_key_exists($key, $this->viewData)) {
      return $this->viewData[$key];
    }
  }
  public function dispatchAction($action) {
    //$actionMethod = "do" . ucfirst($action);
    $action_method = $action;
    if (!method_exists($this, $action_method)) {
      exit("Method [".$action_method."] does not exist in Controller [".$this->getName()."]");
    }
    
    $before_method = 'before_'.$action_method;
    if (method_exists($this, $before_method)) {
      if(!$this->$before_method()) {
        echo 'Filter chain broken on method ['.$before_method.']!';
        exit(0);
      }
    }
    $this->$action_method();
    $this->displayView($action);
  }
  public function displayView($action) {
    //$view_path = VIEW_ROOT . '/' . $this->getName() . '/' . $action . '.'.$this->get_output_format().'.php';
    $this->setVar('flash', $this->flash());
    $view = new \View\Base($this->getName(), $action, $this->viewData, $this->get_output_format(), $this->layout);
    $view->render();
/*    
    //if($this->get_output_format() == 'xhtml') {
    //  $layout = LAYOUT_ROOT.'/'.$this->layout.'.php';
    //}
    
    //$view = VIEW_ROOT . "/" . $this->getName() . "/" . $action . ".php";
    $content = VIEW_ROOT . '/' . $this->getName() . '/' . $action . '.'.$this->get_output_format().'.php';
    //if (!is_file($view)) {
    if (!is_file($content)) {
      //exit("View [".$view."] not found");
      exit('View ['.$content.'] not found');
    }
    //Create variables for the template
    foreach ($this->viewData as $key => $value) {
      $$key = $value;
    }
    
    // support for templates
    if(is_file($layout)) {
      include($layout);
    } else {
      include($content);
    }
    //include $view;
*/
    exit(0);
  }
  
  public function __set($key, $value)  {
    $this->setVar($key, $value);
  }
  public function __get($key) {
    return $this->getVar($key);
  }  
/*  
  public function render_partial($partial_name) {
    $file_name = str_replace('/', '/_', $partial_name);
    include($file_name);
  }
*/  
}
?>