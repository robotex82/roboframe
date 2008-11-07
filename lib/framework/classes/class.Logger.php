<?php
class Logger {
  public function log($message) {
    if(!is_dir("./logs/")) {
      mkdir("./logs");
    }
    $now = date("Y-m-d H:i:s");
    $ip_address = $_SERVER["REMOTE_ADDR"];
    error_log ("[".$ip_address." ".$now." ".$_SERVER['SCRIPT_NAME']."] ".$message."\r\n", 3, "logs/".date("Y-m-d").".log");
  }
  
  public function debug($message) {
    $this->log($message);
  }
  
  public function info($message) {
    $this->log($message);
  }
  
  public function warn($message) {
    $this->log($message);
  }
  
  public function error($message) {
    $this->log($message);
  }
}    
?>