<?php
namespace Controller;
//require_once 'class.Request.php';
abstract class Base {
  private $_logger;
  private $request;
  private static $controller_root = false;
  
  public static function set_controller_root($cr) {
    self::$controller_root = $cr;
  }
  
  public static function controller_root() {
    return self::$controller_root;
  }

  public function set_logger($logger) {
    $this->_logger = $logger;
  }
  
  public function logger() {
    return $this->_logger;
  }
  
  public function set_request($request_data) {
    $this->request = new \Request\Base($request_data);
  }
  
  public function request() {
    return $this->request;
  }
  
  private function get_request_data() {
    return $this->request->get_data();
  }
  /*
  public function params() {
    return $this->get_request_data();
  }
  */
/*  
  public function getRequest() {
  }
*/
  public function getResponse() {
  }
  public function getSession() {
    //I tend to prefer $this->getRequest()->getSession(), but whatever!
  }
  
  public function forward($page, $action, $request_data = array()) {
    //e.g. HomeActions
    $controller_without_underscore = str_replace('_', ' ', $page);
    $controller_as_single_words = ucwords($controller_without_underscore);
    $camelized_controller = str_replace(' ', '', $controller_as_single_words);
    $class = $camelized_controller . "Controller";
    //$class = ucwords($page) . "Controller";
    $file_class = $page . "_controller";
    //e.g. pages/home/HomeActions.php
    $file = \Controller\Base::controller_root() . "/" . $file_class . ".php";
    if (!is_file($file)) {
      if(\Roboframe\Base::environment() == 'production') {
        //TODO: Implement dynamic error pages
        $this->render('page:404.html', 'status:404');
        exit(0);
      }
      exit("Controller [".$file."] not found");
    }
    require_once $file;
    $controller = new $class();
    //$controller->set_request($request_data);
    $controller->set_request($this->get_request_data());
    //$controller->set_name($page);
    $controller->dispatch_action($action);
    exit(0);
  }
  
  public function redirect_to() {
//    print_r(func_get_args());
    $params = array();
    foreach(func_get_args() as $arg) {
      $arg_parts = explode(':', $arg);
      $params[$arg_parts[0]] = $arg_parts[1];
    }  
    $r = new \Router\Base();
 /*
     
    if(isset($params['status'])) {
      switch($params['status']) {
        case '404':
          //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
          header('Location: '.\Router\Base::base_url().'/'.$r->url_for($params), true, 404); 
          exit(0); 
          break;
        default:
          break;  
      }
    }
*/    
    header('Location: '.\Router\Base::base_url().'/'.$r->url_for($params));
  }
  
  public function __construct() {
    
    $this->set_logger(\Logger\Base::logger());
  }
  
  public function render() {
    $params = array();
    foreach(func_get_args() as $arg) {
      $arg_parts = explode(':', $arg);
      $params[$arg_parts[0]] = $arg_parts[1];
    }    
    //var_dump($params);
    if(isset($params['status'])) {
      switch($params['status']) {
        case '404':
          header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
          break;
        default:
          break;  
      }
    }
    if(isset($params['page'])) {
      include(APP_BASE.'/public/'.$params['page']);
    }
  }
}
?>