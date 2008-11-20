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
      $filename = 'logs/'.date("Y-m-d").'.log';
    }
    if(!is_dir("./logs/")) {
      mkdir("./logs");
    }
    $now = date("Y-m-d H:i:s");
    $ip_address = $_SERVER['REMOTE_ADDR'].' ';
    error_log ('['.$ip_address.$now.' '.$_SERVER['SCRIPT_NAME'].'] '.$message."\r\n", 3, $filename);
  }
}    
?>