<?php
abstract class ActionController extends Controller {
  protected $name;
  protected $viewData = array();
  public    $layout = 'default';
  
  public function setName($name) {
    $this->name = $name;
  }
  public function getName() {
    return $this->name;
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
    $actionMethod = $action;
    if (!method_exists($this, $actionMethod)) {
      exit("Method [".$actionMethod."] does not exist in Controller [".$this->getName()."]");
    }
    $this->$actionMethod();
    $this->displayView($action);
  }
  public function displayView($action) {
    $layout = LAYOUT_ROOT.'/'.$this->layout.'.php';
    //$view = VIEW_ROOT . "/" . $this->getName() . "/" . $action . ".php";
    $content = VIEW_ROOT . '/' . $this->getName() . '/' . $action . '.php';
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