<?php
namespace Output\Manager;
class Base {
  static private $handlers = array();
  
  static public function register_handler($name, $path) {
    if(!is_readable($path)) {
      throw new \Exception("Could not regiester Output Handler [{$name}]! File [{$path}] is not readable!");
    }
    self::$handlers[$name] = realpath($path); 
  }
  
  static public function unregister_handler($name) {
    unset(self::$handlers[$name]);
  }
  
  static public function handlers() {
    ksort(self::$handlers);
    return self::$handlers;
  }
  
  static public function handler_path($name) {
    if(!isset(self::$handlers[$name])) {
      throw new \Exception("Output Handler [{$name}] is not registered!");
    }
    return self::$handlers[$name];
  }
  
  
  static public function auto_register_framework_output_handlers() {
    foreach(glob(realpath(FRAMEWORK_PATH.'/classes/Output/Handler').'/*.php') as $path) {
      $name = strtolower(basename($path, '.php'));
      //echo $name." => ".$path."\r\n";
      self::register_handler($name, $path);
    }
  }
  
  static public function factory($handler_name, $options) {
    $base_handler_path = self::handler_path('base');
    require_once($base_handler_path);
    
    $handler_path = self::handler_path($handler_name);
    $handler_class = "\\Output\\Handler\\".\Inflector\Base::camelize($handler_name);
    
   
    require_once($handler_path);
    $handler = new $handler_class($options);
    
    return $handler;
  }
    
  //protected $output_format = 'xhtml';
  //protected $view_data = array();
  protected $output_manager = null;
  
  public static function get_output_manager_for($output_format) {
      //echo $output_format;
      $exploded_output_format = explode('|', $output_format);
      $output_handler = $exploded_output_format[0];
      unset($exploded_output_format[0]);
      $options = array_values($exploded_output_format);
      
      $oh = self::factory($output_handler, $options);
      //var_dump($oh);
      //var_dump($exploded_output_format);
      return $oh;
  }
/*  
  public static function get_output_manager_for($output_format) {
    $exploded_output_format = explode('|', $output_format);
    switch ($exploded_output_format[0]) {
    case 'csv':
      //require_once(FRAMEWORK_PATH.'/classes/class.OutputCsv.php');
      \Roboframe\Base::enable_module('Output\Csv');
      return new \Output\Csv();
    case 'xhtml':
      //require_once(FRAMEWORK_PATH.'/classes/class.OutputXhtml.php');
      \Roboframe\Base::enable_module('Output\Xhtml');
      $output = new \Output\Xhtml();
      if(isset($exploded_output_format[1]) && $exploded_output_format[1] == 'gzip') {
        $output->enable_gzip();
      }
      return $output;
    case 'file':
      //require_once(FRAMEWORK_PATH.'/classes/class.OutputFile.php');
      \Roboframe\Base::enable_module('Output\File');
      $output = new \Output\File();
      if(isset($exploded_output_format[1])) {
        $output->set_file($exploded_output_format[1]);
      }
      if(isset($exploded_output_format[2])) {
        $output->set_filename($exploded_output_format[2]);
      }
      if(isset($exploded_output_format[3])) {
        $output->set_mimetype($exploded_output_format[3]);
      }
      return $output;
    case 'pdf':
      //require_once(FRAMEWORK_PATH.'/classes/class.OutputPdf.php');
      \Roboframe\Base::enable_module('Output\Pdf');
      $output = new \Output\Pdf();
      if($exploded_output_format[1] == 'download') {
        $output->enable_gzip();
      }
      $output->direct_download();
      return $output;
    default:
      throw new Exception('No output handler for format ['.$output_format.'] defined');
    }
  }
  */
}
?>