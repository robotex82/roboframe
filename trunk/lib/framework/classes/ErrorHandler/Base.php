<?php
namespace ErrorHandler;
use Exception;
class Base {
  static protected $_throw_errors = false;
  
  static public function set_throw_errors($throw_errors) { self::$_throw_errors = $throw_errors; }
  static public function throw_errors() { return self::$_throw_errors; }
  
  
  public static function handle($error_code, $error_string, $error_file, $error_line) {
    $error_reporting = ini_get('error_reporting');
    if(!($error_reporting & $error_code)) 
      return false;

    switch ($error_code) {
      case E_NOTICE:
      case E_USER_NOTICE:
          $errors = "Notice";
          break;
      case E_WARNING:
      case E_USER_WARNING:
          $errors = "Warning";
          break;
      case E_ERROR:
      case E_USER_ERROR:
          $errors = "Fatal Error";
          break;
      default:
          $errors = "Unknown";
          break;
    }

    if (ini_get("display_errors")) {
      if(static::throw_errors()) {
        throw new Exception(sprintf("<br />\n<b>%s</b>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $errors, $error_string, $error_file, $error_line));
      } else {
        printf ("<br />\n<b>%s</b>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $errors, $error_string, $error_file, $error_line);
      }  
    }    
    if (ini_get('log_errors')) {
      error_log(sprintf("PHP %s:  %s in %s on line %d", $errors, $error_string, $error_file, $error_line));
    }
    return true;
  }
}