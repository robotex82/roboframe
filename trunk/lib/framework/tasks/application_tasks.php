<?php
namespace TaskGroup;
class ApplicationTasks extends Base {
  protected static $tasks = array('list_controllers' => ''
                                , 'list_actions'     => ''
                                , 'environment'      => '');

  public function list_controllers() {
    foreach(\Roboframe\Base::list_application_controllers() as $controller) {
      echo $controller."\r\n";
    }
  }
  
  public function list_actions() {
    foreach(\Roboframe\Base::list_application_controller_files() as $controller_dir_entry) {
      echo "\r\n".$controller_dir_entry;
      foreach(\Roboframe\Base::list_controller_actions($controller_dir_entry) as $action) {
        echo "\r\n  ".$action.'()';  
      }
    }
  }
  
  public function environment() {
    echo \Roboframe\Base::environment();
  }
}