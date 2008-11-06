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
/*  
  public function getRequest() {
  }
*/
  public function getResponse() {
  }
  public function getSession() {
    //I tend to prefer $this->getRequest()->getSession(), but whatever!
  }
  
  public function forward($page, $action) {
    //e.g. HomeActions
    $class = ucfirst($page) . "Controller";
    $file_class = $page . "_controller";
    //e.g. pages/home/HomeActions.php
    $file = CONTROLLER_ROOT . "/" . $file_class . ".php";
    if (!is_file($file)) {
      exit("Page [".CONTROLLER_ROOT . "/" . $class . ".php] not found");
    }
    require_once $file;
    $controller = new $class();
    $controller->set_request($_REQUEST);
    $controller->setName($page);
    $controller->dispatchAction($action);
    exit(0);
  }
}
?>