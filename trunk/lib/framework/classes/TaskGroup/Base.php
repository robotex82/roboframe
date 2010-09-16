<?php
namespace TaskGroup;
use \ReflectionMethod;
/**
 * TaskGroup Baseclass. All TaskGroups extend this class
 * 
 * A TaskGroup is a collection of methods, that run different tasks in a defined domain.
 * 
 * Tasks may have prerequistes (ohter tasks)
 * 
 * Create a new TaskGroup by typing:
 * 
 * php scripts/generate.php task_group <CamelCasedName>
 * 
 * @author vasquezr
 *
 */
class Base {
  public static function run($taskname, $options = array(), $prerequisites_options_chain = array()) {
    if(!method_exists(get_called_class(), $taskname)) {
      throw new \exception('Can not run unknown task ['.$taskname.']');
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
  
  public static function task_exists($taskname) {
    return in_array($taskname, self::available());
  }
  
  public static function documentation_for_task($taskname) {
    $r = new ReflectionMethod(get_called_class(), $taskname);
    return $r->getDocComment(); 
  }
}