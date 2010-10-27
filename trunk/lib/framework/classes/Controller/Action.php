<?php
namespace Controller;
use Exception;
use Logger\Base as Logger;
abstract class Action extends Base {
  protected $_name;
  protected $_action;
  protected $viewData      = array();
  public    $layout; //        = 'default';
  public    $output_format = 'xhtml';
  protected $flash;
  static protected $_before_filters = array();
  
  public function flash() {
    return $this->flash;
  }
  
  public function __construct() {
    parent::__construct();
    $this->set_name(str_replace('_controller', '', \Inflector\Base::underscore(get_called_class())));
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
  
  public function set_name($name) {
    $this->_name = $name;
  }
  public function getName() {
    return $this->_name;
  }
  
  public function controller_name() {
    return $this->_name;
  }
  
  public function set_action_name($action_name) {
    $this->_action_name = $action_name;
  }
  public function action_name() {
    return $this->_action_name;
  }
  
  public function setVar($key, $value) {
    $this->viewData[$key] = $value;
  }
  public function getVar($key) {
    if (array_key_exists($key, $this->viewData)) {
      return $this->viewData[$key];
    }
  }
  public function dispatch_action($action) {
    //$actionMethod = "do" . ucfirst($action);
    $this->set_action_name($action);
    $action_method = $action;
    if (!method_exists($this, $action_method)) {
      if(\Roboframe\Base::environment() == 'production') {
        //TODO: Implement dynamic error pages
        $this->render('page:404.html', 'status:404');
        exit(0);
      }
      exit("Method [".$action_method."] does not exist in Controller [".$this->getName()."]");
    }
    if(method_exists($this, 'init')) {
      $this->init();
    }
    
    
    //var_dump(static::$_before_filters);
    foreach(static::$_before_filters as $bf) {
      if(!array_key_exists('except', $bf) && !array_key_exists('only', $bf)) {
        if(!$this->invoke_callback($bf[0])) {
          echo 'Filter chain broken on method ['.$bf[0].']!';
          exit();
        }
        continue;
      }
      if(array_key_exists('except', $bf) && !in_array($action_method, $bf['except'])) {
              if(!$this->invoke_callback($bf[0])) {
          echo 'Filter chain broken on method ['.$bf[0].']!';
          exit();
        }
        continue;
      }
      
      if(array_key_exists('only', $bf) && in_array($action_method, $bf['only'])) {
        if(!$this->invoke_callback($bf[0])) {
          echo 'Filter chain broken on method ['.$bf[0].']!';
          exit();
        }
        continue;
      }      
    }

/*
    //var_dump($this->get_before_filters());
    foreach($this->get_before_filters() as $before_method => $exceptions) {
      if(!isset($exceptions['except']) && !isset($exceptions['only'])) {
        if (method_exists($this, $before_method)) {
          #var_dump($exceptions);
          if(!$this->$before_method()) {
            echo 'Filter chain broken on method ['.$before_method.']!';
            exit(0);
          }
        } 
      }
      if(isset($exceptions['except']) && !in_array($action_method, $exceptions['except'])) {
        if (method_exists($this, $before_method)) {
          #var_dump($exceptions);
          if(!$this->$before_method()) {
            echo 'Filter chain broken on method ['.$before_method.']!';
            exit(0);
          }
        }   
      }
      if(isset($exceptions['only']) && in_array($action_method, $exceptions['only'])) {
        if (method_exists($this, $before_method)) {
          #var_dump($exceptions);
          if(!$this->$before_method()) {
            echo 'Filter chain broken on method ['.$before_method.']!';
            exit(0);
          }
        }   
      }
    }
    
    $before_method = 'before_'.$action_method;
    if (method_exists($this, $before_method)) {
      if(!$this->$before_method()) {
        echo 'Filter chain broken on method ['.$before_method.']!';
        exit(0);
      }
    }
*/
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
  
  private function invoke_callback($method_name, $must_exist = true) {
    if(method_exists($this, $method_name)) {
      Logger::logger()->debug("Invoking callback [{$method_name}]");
      return $this->$method_name();
    }
    
    if($must_exist) {
      throw new Exception("Called non existing callback [{$method_name}]");
    }
    
    return true;
  }
  
  protected function register_before_filter($bf, $first = false) {
    if($first) {
      static::$_before_filters = array_pad(static::$_before_filters, -(count(static::$_before_filters) + 1), $bf);
    } else {
      array_push(static::$_before_filters, $bf);  
    }
    
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