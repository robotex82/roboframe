<?php
require_once(FRAMEWORK_PATH.'/classes/class.Database.php');
abstract class Model {
  protected $data = array();
  
  public $database_connection = null;
  
  function __construct() {
    $this->database_connection = Database::get_connection();
  }
  
  public function __set($key, $value)  {
    $this->set_var($key, $value);
  }
  public function __get($key) {
    return $this->get_var($key);
  } 
  
  public function set_var($key, $value) {
    $this->data[$key] = $value;
  }
  public function get_var($key) {
    if (array_key_exists($key, $this->data)) {
      return $this->data[$key];
    }
  }
}
?>