<?php
require_once(FRAMEWORK_PATH.'/classes/class.Database.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.PresenceOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.LengthOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.InclusionOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.FormatOfValidator.php');

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

  protected function validates_presence_of() {
    $args = func_get_args();
    if(!is_string($args[0])) {
      throw new Exception('Expected first param to be a String!');
    }
    $fields = array();
    $fields = explode(',', $args[0]);    
    for($i = 0;$i < count($fields); $i++) {
      $fields[$i] = trim($fields[$i]);
    }
    $this->validators[] = new PresenceOfValidator($this, $fields);
  }
  
  protected function validates_length_of() {
    $args = func_get_args();
    if(count($args) == 0) {
      throw new Exception('Expected to get at least one length definition!');
    }
    
    foreach($args as $condition) {
      $condition_parts = explode(' is ', $condition);
      $this->validators[] = new LengthOfValidator($this, $condition_parts[0], $condition_parts[1]);
    }
  }
  
  protected function validates_inclusion_of() {
    $args = func_get_args();
    if(count($args) != 2) {
      throw new Exception('Expected to get at two parameters for method Model::validates_inclusion_of()!');
    }  
    $field = $args[0];
    $possible_values = explode(', ', $args[1]);
    $this->validators[] = new InclusionOfValidator($this, $field, $possible_values);
    
  }
  
  protected function validates_format_of($field, $pattern) {
    $this->validators[] = new FormatOfValidator($this, $field, $pattern);
  }
  
  public function validate() {
    foreach($this->validators as $validator) {
      if(!$validator->validate()) {
        return false;
      }
    }
    return true;
  }
}
?>