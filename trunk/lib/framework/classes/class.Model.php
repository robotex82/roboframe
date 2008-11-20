<?php
require_once(FRAMEWORK_PATH.'/classes/class.Database.php');
abstract class Model {
  protected $data = array();
  protected $validators = array();
  
  //public $database_connection = null;
  
  public function __construct() {
    //$this->database_connection = Database::get_connection();
    if(method_exists($this, 'init')) {
      $this->init();
    }
  }
  
  public function database_connection($connection_name = false) {
    return Database::get_connection($connection_name);
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
/*  
  public function __call($method, $args) {
    if(substr($method, 0, 8) == 'validate_') {
      echo 'Call for validation => '.substr($method, 8, 0).'!';
      $validator_type = substr($method, 8, 0);
      $options_as_array = array_slice($args, 1);
      $this->add_validator(ValidationManager::get_validator($validator_type, $args[0], $options_as_array));
    }
  }
  
  private function add_validator($validator) {
    $this->validators[] = $validator;
  }
  
  public function validate() {
    $return_value = true;
    foreach($this->validators as $v) {
      if(!$v->validate()) {
        $return_value = false;
        $this->add_to_error_messages($v->get_error_message());
      }
    }
    return $return_value;
  }
*/
}
?>