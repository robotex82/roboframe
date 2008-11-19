<?php
//require_once dirname(__FILE__) . "/class.ActionController.php";
require_once 'class.Controller.php';
require_once 'class.ActionController.php';

class FrontController extends Controller {
  public static function createInstance() {
    if (!defined("CONTROLLER_ROOT") || !defined("VIEW_ROOT") || !defined("LAYOUT_ROOT")) {
      exit("Critical error: Cannot proceed without CONTROLLER_ROOT, VIEW_ROOT and LAYOUT_ROOT.");
    }
    $instance = new self();
    //$instance->set_request($_REQUEST);
    return $instance;
  }
  public function dispatch() {
/*
    $page = !empty($_GET["controller"]) ? $_GET["controller"] : "home";
    $action = !empty($_GET["action"]) ? $_GET["action"] : "index";
    $this->forward($page, $action);
*/    
//print_r($_GET['url']);
    $r = new Router($_GET['url']);
    $r->match_all_routes();
    $this->forward($r->get_controller_name(), $r->get_action_name(), $r->get_request_params());
  }
}
?>