<?php
namespace Generator\Manager;
class Base {
  static private $generators = array();
  
  static public function register_generator($name, $path) {
    self::$generators[$name] = $path; 
  }
  
  static public function unregister_generator($name) {
    unset(self::$generators[$name]);
  }
  
  static public function generators() {
    ksort(self::$generators);
    return self::$generators;
  }
  
  static public function generator_path($name) {
    if(!isset(self::$generators[$name])) {
      throw new \Exception("Generator [{$name}] is not registered!");
    }
    return self::$generators[$name];
  }
  
  
  static public function auto_register_framework_generators() {
    foreach(glob(realpath(FRAMEWORK_PATH.'/generators').'/*') as $path) {
      $exploded_path = explode('/', $path);
      $name = end($exploded_path);
      //echo $name." => ".$path."\r\n";
      self::register_generator($name, $path);
    }
  }
  
  static public function factory($generator_name) {
    $generator_path = self::generator_path($generator_name);
    $generator_class = "\\Generator\\".\Inflector\Base::camelize($generator_name);
    
    require_once($generator_path.'/generator.php');
    $generator = new $generator_class();
    
    return $generator;
  }
}
