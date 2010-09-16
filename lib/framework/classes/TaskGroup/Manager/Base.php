<?php
namespace TaskGroup\Manager;
/**
 * The TaskGroup Manager class stores all registered Taskgroups
 * 
 * Registering a TaskGroup
 * <code>
 * \TaskGroup\Manager\Base::register_task_group('post', '/path/to/post_tasks.php');
 * </code>
 * 
 * 
 * @author Roberto Vasquez Angel
 *
 */
class Base {
  static private $task_groups = array();
  
  static public function register_task_group($name, $path) {
    self::$task_groups[$name] = $path; 
  }
  
  static public function unregister_task_group($name) {
    unset(self::$task_groups[$name]);
  }
  
  static public function task_groups() {
    ksort(self::$task_groups);
    return self::$task_groups;
  }
/*  
  static public function task_group_path($name) {
    if(!isset(self::$task_groups[$name])) {
      throw new \Exception("TaskGroup [{$name}] is not registered!");
    }
    return self::$task_groups[$name];
  }
*/  
  
  static public function auto_register_framework_task_groups() {
    foreach(glob(realpath(FRAMEWORK_PATH.'/tasks').'/*') as $path) {
      //$name = end(explode('/', $path));
      $name = basename($path, '_tasks.php');
      //echo $name." => ".$path."\r\n";
      self::register_task_group($name, $path);
    }
  }
  
  static public function auto_register_application_task_groups() {
    if(is_dir(realpath(APP_BASE.'/lib/tasks'))) {
      foreach(glob(realpath(APP_BASE.'/lib/tasks').'/*') as $path) {
        //$name = end(explode('/', $path));
        $name = basename($path, '_tasks.php');
        //echo APP_BASE."\r\n";
        //echo $name." => ".$path."\r\n";
        self::register_task_group($name, $path);
      }    
    }
  }
/*  
  static public function factory($task_group_name) {
    $task_group_path = self::task_group_path($task_group_name);
    $task_group_class = "\\task_group\\".\Inflector\Base::camelize($task_group_name);
    
    require_once($task_group_path.'/task_group.php');
    $task_group = new $task_group_class();
    
    return $task_group;
  }
*/
  static public function find_and_load($name) {
    if(!isset(self::$task_groups[$name])) {
      return false;
    }
    
    if(!is_readable(self::$task_groups[$name])) {
      echo "Could not read [{self::$task_groups[$name]}]!";
    }
    require_once(self::$task_groups[$name]);
    
    return "\\TaskGroup\\".\Inflector\Base::camelize($name).'Tasks';
    //return self::$task_groups[$name];
  }

  public static function documentation_for_task_group($taskgroup) {
    $r = new ReflectionClass($taskgroup);
    return $r->getDocComment(); 
  }
}