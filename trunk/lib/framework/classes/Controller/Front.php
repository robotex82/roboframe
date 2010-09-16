<?php
namespace Controller;
//require_once dirname(__FILE__) . "/class.ActionController.php";
//require_once 'class.Controller.php';
//require_once 'class.ActionController.php';

class Front extends Base {
  public static function createInstance() {
    if (!\Controller\Base::controller_root()) {
      exit('Critical error: Cannot proceed without valid \Controller\Base::controller_root()');
    }
    
    if (!\View\Base::view_root()) {
      exit('Critical error: Cannot proceed without valid \View\Base::view_root()');
    }
    
    if (!\View\Base::layout_root()) {
      exit('Critical error: Cannot proceed without valid \View\Base::layout_root()');
    }
    //$instance = new self();
    //return $instance;
    return new self();
  }
  public function dispatch() {
/*
    $page = !empty($_GET["controller"]) ? $_GET["controller"] : "home";
    $action = !empty($_GET["action"]) ? $_GET["action"] : "index";
    $this->forward($page, $action);
*/    
//print_r($_GET['url']);
    $r = new \Router\Base($_GET['url']);
    $r->match_all_routes();
    $this->set_request($r->get_request_params());
    $this->forward($r->get_controller_name(), $r->get_action_name());
    //$this->forward($r->get_controller_name(), $r->get_action_name(), $r->get_request_params());
  }
}
?>