<?php
namespace TaskGroup;
class Base {
  public static function run($taskname, $options = array(), $prerequisites_options_chain = array()) {
    if(!method_exists(get_called_class(), $taskname)) {
      throw new exception('Can not run unknown task ['.$taskname.']');
    }
    if(method_exists(get_called_class(), static::$tasks[$taskname])) {
      $prerequisite = static::$tasks[$taskname];
      $prerequisites_options = $prerequisites_options_chain[0];
      unset($prerequisites_options_chain[0]);
      $prerequisites_options_chain = array_values($prerequisites_options_chain);
      if(!self::run($prerequisite, $prerequisites_options, $prerequisites_options_chain)) {
        return false;
      }
    }
    return static::$taskname($options);
  }
  
  public static function available() {
    return array_keys(static::$tasks);
  }
  
  static public function search($name) {
    if(is_readable(APP_BASE.'/lib/tasks/'.$name.'_tasks.php')) {
      require_once APP_BASE.'/lib/tasks/'.$name.'_tasks.php';
      return APP_BASE.'/lib/tasks/'.$name.'_tasks.php';
    }

  if(is_readable(FRAMEWORK_PATH.'/tasks/'.$name.'_tasks.php')) {
      require_once(FRAMEWORK_PATH.'/tasks/'.$name.'_tasks.php');
      return FRAMEWORK_PATH.'/tasks/'.$name.'_tasks.php';
    }
  }
}