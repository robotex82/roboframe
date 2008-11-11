<?php
class Roboframe {
  public static function list_application_controllers() {
    $controllers = array();
    foreach(Roboframe::list_application_controller_files() as $controller_dir_entry) {
      $controller_name = str_replace('_controller.php', '', $controller_dir_entry);
      $camelized_controller_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller_name)));
      $controllers[] = $camelized_controller_name;
    }
    return $controllers;
  }
/*  
  public static function list_application_actions() {
    $application_actions = array();
    foreach(Roboframe::list_application_controller_files() as $controller_dir_entry) {
      $application_actions[] = Roboframe::list_controller_actions($controller_dir_entry);
    }
    return $application_actions;
  }
*/  
  public static function list_controller_actions($controller_file_name) {
    $file_content = file(APPLICATION_ROOT.'/controllers/'.$controller_file_name);
    $pattern = '/^[ ]*public function [ ]*(.*)\(/';
    $controller_actions = array();
    foreach($file_content as $line) {
      if(preg_match($pattern, $line, $matches)) {
        $controller_actions[] = $matches[1];
      }
    }
    return $controller_actions;
  }
  
  public static function list_application_controller_files() {
    $controller_dir = APPLICATION_ROOT.'/controllers';
    if(!is_dir($controller_dir)) {
      throw new Exception('The Controller directory ['.$controller_dir.'] is missing!');
    }
    
    if ($handle = opendir($controller_dir)) {
      $controllers = array();
      while (false !== ($controller_dir_entry = readdir($handle))) {
        if ($controller_dir_entry != "." && $controller_dir_entry != "..") {
          $controllers[] = $controller_dir_entry;
        }
      }
      closedir($handle);
    }
    return $controllers;
  }
  
  public static function enable_sessions() {
    session_start();
  }
}    
?>