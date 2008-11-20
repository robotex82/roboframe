<?php
class Logger {
  public function log($message) {
    Logger::write($message);
  }
  
  public function debug($message) {
    Logger::write($message);
  }
  
  public function info($message) {
    Logger::write($message);
  }
  
  public function warn($message) {
    Logger::write($message);
  }
  
  public function error($message) {
    Logger::write($message);
  }
  
  public static function write($message, $filename = false) {
    if(!$filename) {
      $filename = APP_BASE.'/logs/'.date("Y-m-d").'.log';
    }
    if(!is_dir(dirname($filename))) {
      throw new Exception('Could not write log! Directory ['.dirname($filename).'] is missing!');
    }
    $now = date("Y-m-d H:i:s");
    $ip_address = $_SERVER['REMOTE_ADDR'].' ';
    error_log ('['.$ip_address.$now.' '.$_SERVER['SCRIPT_NAME'].'] '.$message."\r\n", 3, $filename);
  }
}    
?>