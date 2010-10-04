<?php
namespace Logger;
abstract class Base {
  static protected $logger;
  static protected $_log_debug_messages = true;
  static protected $_log_info_messages = true;
  static protected $_log_warn_messages = true;
  static protected $_log_error_messages = true;

  static public function set_log_debug_messages($log_debug_messages) { static::$_log_debug_messages = $log_debug_messages; }
  static public function log_debug_messages() { return static::$_log_debug_messages; }
  
  static public function set_log_error_messages($log_error_messages) { self::$_log_error_messages = $log_error_messages; }
  static public function log_error_messages() { return self::$_log_error_messages; }
  
  static public function set_log_info_messages($log_info_messages) { static::$_log_info_messages = $log_info_messages; }
  static public function log_info_messages() { return static::$_log_info_messages; }
  
  static public function set_log_warn_messages($log_warn_messages) { self::$_log_warn_messages = $log_warn_messages; }
  static public function log_warn_messages() { return self::$_log_warn_messages; }
  
  
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