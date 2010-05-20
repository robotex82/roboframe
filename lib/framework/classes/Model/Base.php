<?php
namespace Model;
/*
require_once(FRAMEWORK_PATH.'/classes/class.Database.php');
require_once(FRAMEWORK_PATH.'/classes/validators/PresenceOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/LengthOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/InclusionOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/FormatOfValidator.php');
*/
abstract class Base {
  protected $data = array();
  protected $validators = array();
  protected $errors = array();
  protected $_connection_name = false;
  
  //public $database_connection = null;
  
  public function __construct() {
    //$this->database_connection = Database::get_connection();
    if(method_exists($this, 'initialize')) {
      $this->initialize();
    }
    
    $args = func_get_args();
    if(isset($args[0]) and is_array($args[0])) {
      $this->data = $args[0];
    }
  }
  
  public function database_connection() {
    return Database::get_connection($this->_connection_name);
  }
  
  public function set_database_connection($connection_name) {
    $this->_connection_name = $connection_name;
    
  }
  
  public function __set($key, $value)  {
    $this->set_var($key, $value);
  }
  public function __get($key) {
    return $this->get_var($key);
  } 
  
  private function set_var($key, $value) {
    $this->data[$key] = $value;
  }
  private function get_var($key) {
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
    $this->validators[] = new PresenceOfValidator($this, $fields, $args[1]);
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
      throw new Exception('Expected to get two parameters for method Model::validates_inclusion_of()!');
    }  
    $field = $args[0];
    $possible_values = explode(', ', $args[1]);
    $this->validators[] = new InclusionOfValidator($this, $field, $possible_values);
    
  }
  
  protected function validates_format_of($field, $pattern, $message) {
    $this->validators[] = new FormatOfValidator($this, $field, $pattern, $message);
  }
  
  public function validate() {
    foreach($this->validators as $validator) {
      if(!$validator->validate()) {
        return false;
      }
    }
    return true;
  }
/*  
  public function add_to_errors($field, $message) {
    $this->errors[$field] = $message;
  }
*/
  public function set_error_message_for($field, $message) {
    $this->errors[$field] = $message;
  }
  
  public function remove_error_message_for($field) {
    unset($this->errors[$field]);
  }
  
  public function get_error_messages() {
    return $this->errors;
  }
  
  public function error_message_for($fieldname) {
    if(array_key_exists($fieldname, $this->errors)) {
      return $this->errors[$fieldname];
    }
  }
  
  public function init() {

    spl_autoload_extensions('.php');
    spl_autoload_register('self::class_loader');
    
  }
  
  public static function class_loader($class) {
    $file = APPLICATION_ROOT.'/models/'.\Inflector\Base::underscore($class).'.php';
    if (!is_readable($file)) {
      return false;
    }
    include $file; 
  }

}
?>