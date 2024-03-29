<?php
namespace Logger;
use Exception;
\Roboframe\Base::enable_module('Logger\Base');
class Text extends Base {
  private static $filename;
  private $instance_filename;
  
    /**
    * Set the instance_filename value
    * @param type $instance_filename
    */
    public function set_instance_filename($instance_filename) {
      $this->instance_filename = $instance_filename;
    }
  
    /**
    * Returns the instance_filename value.
    * @return type
    */
    public function instance_filename() {
      return $this->instance_filename;
    }
  
  
  public static function set_filename($filename) {
    /*
    if(!is_writable($filename)) {
       throw new \Exception("Can't write to file [".$filename."]");
    } 
    */
    self::$filename = $filename;  
  }

  public static function filename() {
    return self::$filename;    
  }
  
  public function __construct($filename = null) {
    if(is_null($filename)) {
      $this->set_instance_filename(self::filename());
    } else {
      $this->set_instance_filename($filename);
    }
  }
  
  public static function init() {
    \Logger\Base::set_logger(new static(APP_BASE.'/logs/'.\Roboframe\Base::environment().'_'.date("Y-m-d").'.log'));
  }
  
  public function log($message) {
    $this->write($message);
  }
  
  public function debug($message) {
    if(self::log_debug_messages()) {
      $this->write('DEBUG: '.$message);
    }
  }
  
  public function info($message) {
    if(self::log_info_messages()) {
      $this->write('INFO: '.$message);
    }
  }
  
  public function warn($message) {
    if(self::log_warn_messages()) {
      $this->write('WARNING: '.$message);
    }
  }
  
  public function error($message) {
    if(self::log_error_messages()) {
      $this->write('ERROR: '.$message);
    }
  }
  
  public function write($message) {
    //$filename = self::$filename;
    $filename = $this->instance_filename();
    if(!is_dir(dirname($filename))) {
      throw new \Exception('Could not write log! Directory ['.dirname($filename).'] is missing!');
    }
    $now = date("Y-m-d H:i:s");
    $ip_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'].' ' : ' ';
    error_log ('['.$ip_address.$now.' '.$_SERVER['SCRIPT_NAME'].'] '.$message."\r\n", 3, $filename);
  }
}