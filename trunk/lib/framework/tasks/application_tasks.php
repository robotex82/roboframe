<?php
namespace TaskGroup;
class ApplicationTasks extends Base {
  protected static $tasks = array('list_controllers' => ''
                                , 'list_actions'     => ''
                                , 'environment'      => ''
                                , 'output_handlers'  => '');

  public static function list_controllers() {
    foreach(\Roboframe\Base::list_application_controllers() as $controller) {
      echo $controller."\r\n";
    }
  }
  
  public static function list_actions() {
    foreach(\Roboframe\Base::list_application_controller_files() as $controller_dir_entry) {
      echo "\r\n".(str_replace('.php', '', $controller_dir_entry));
      foreach(\Roboframe\Base::list_controller_actions($controller_dir_entry) as $action) {
        echo "\r\n  ".$action;  
      }
    }
  }
  
  public static function environment() {
    echo \Roboframe\Base::environment();
  }
  
  public static function output_handlers() {
    \Output\Manager\Base::auto_register_framework_output_handlers();
    foreach(\Output\Manager\Base::handlers() as $type => $path) {
      echo "{$type} => {$path}\r\n";
    }
  }
}