<?php
require_once 'class.Request.php';
abstract class Controller {
  private $request;
  
  public function set_request($request_data) {
    $this->request = new Request($request_data);
  }
  
  public function request() {
    return $this->request;
  }
  
  private function get_request_data() {
    return $this->request->get_data();
  }
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
    $file = CONTROLLER_ROOT . "/" . $file_class . ".php";
    if (!is_file($file)) {
      exit("Controller [".$file."] not found");
    }
    require_once $file;
    $controller = new $class();
    //$controller->set_request($request_data);
    $controller->set_request($this->get_request_data());
    $controller->setName($page);
    $controller->dispatchAction($action);
    exit(0);
  }
  
  public function redirect_to() {
//    print_r(func_get_args());
    $params = array();
    foreach(func_get_args() as $arg) {
      $arg_parts = explode(':', $arg);
      $params[$arg_parts[0]] = $arg_parts[1];
    }  
    $r = new Router();
    header('Location: '.Router::base_url().'/'.$r->url_for($params));
  }
}
?>