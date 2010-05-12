<?php
namespace Logger;
class Text extends Base {
  private static $filename;
  
  
  public function set_filename($filename) {
    /*
    if(!is_writable($filename)) {
       throw new \Exception("Can't write to file [".$filename."]");
    } 
    */
    self::$filename = $filename;  
  }

  public function filename() {
    return self::filename;    
  }
  
  public function __construct($filename = null) {
    if(!is_null($filename)) {
      $this->set_filename($filename);
    }
  }
  
  public static function init() {
    \Logger\Base::set_logger(new self(APP_BASE.'/logs/'.\Roboframe\Base::environment().'_'.date("Y-m-d").'.log'));
  }
  
  public function log($message) {
    $this->write($message);
  }
  
  public function debug($message) {
    $this->write($message);
  }
  
  public function info($message) {
    $this->write($message);
  }
  
  public function warn($message) {
    $this->write($message);
  }
  
  public function error($message) {
    $this->write($message);
  }
  
  public function write($message) {
    $filename = self::$filename;

    if(!is_dir(dirname($filename))) {
      throw new \Exception('Could not write log! Directory ['.dirname($filename).'] is missing!');
    }
    $now = date("Y-m-d H:i:s");
    $ip_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null.' ';
    error_log ('['.$ip_address.$now.' '.$_SERVER['SCRIPT_NAME'].'] '.$message."\r\n", 3, $filename);
  }
}    
?>