<?php
namespace Logger;
abstract class Base {
  static protected $logger;
  
  static public function set_logger($l) {
    self::$logger = $l;
  }
  
  static public function logger() {
    return self::$logger;
  }
  
  abstract public function log($message);
  abstract public function debug($message);
  abstract public function info($message);
  abstract public function warn($message);
  abstract public function error($message);
  abstract public function write($message);
}    
?>