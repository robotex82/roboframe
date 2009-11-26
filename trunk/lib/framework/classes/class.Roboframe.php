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
//        if ($controller_dir_entry != "." && $controller_dir_entry != "..") {
        if (substr($controller_dir_entry, 0, 1) != '.') {
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

  public static function check_requirements() {
    Roboframe::check_needed_libs();
  }

  public static function check_needed_libs() {
    if(!is_dir(LIBRARY_PATH.'/adodb')) {
      die('Could not find adodb library in ['.LIBRARY_PATH.'/adodb]. Please make sure you installed adodb!');
    }

    if(!is_dir(LIBRARY_PATH.'/fpdf')) {
      die('Could not find fpdf library in ['.LIBRARY_PATH.'/fpdf]. Please make sure you installed fpdf!');
    }

    if(!is_dir(LIBRARY_PATH.'/simpletest')) {
      die('Could not find simpletest library in ['.LIBRARY_PATH.'/simpletest]. Please make sure you installed simpletest!');
    }
  }

  public static function camel_case_to_underscore($camel_cased) {
    $underscored = str_replace(' ', '_', strtolower(preg_replace('/([^\s])([A-Z])/', '\1 \2', $camel_cased)));
    return $underscored;
  }
  public static function dissect_args($args_array, $omit_from_start = 0, $omit_from_end = 0) {
    if(!is_array($args_array)) {
      return array();
    }
    $options = array();
    for($i = 0; $i < $omit_from_start; $i++) {
      unset($args_array[$i]);
      //echo "unsetting...{$i}";
    }

    for($i = count($args_array); $i > count($args_array) - $omit_from_end; $i--) {
      unset($args_array[$i - 1]);
      //echo "unsetting...{$i}";
    }
    foreach($args_array as $value) {
      $exploded_value = explode(' => ', $value);
      $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
    }
    return $options;
  }

  public static function enable_database() {
    Roboframe::enable_module('Database');
    /*
    $registry = Registry::instance();
    $registry->set_entry('database_connection', Database::get_connection());
    */
  }

  public static function disable_database() {
    $registry = Registry::instance();
    $registry->remove_entry('database_connection');
  }

  public static function enable_module($module_name) {
    require_once(FRAMEWORK_PATH.'/classes/class.'.$module_name.'.php');
    if(method_exists($module_name, 'init')) {
      $module_name::init();
    }
    
  }

  public static function timezone($timezone) {
    date_default_timezone_set($timezone);
  }
}
?>