<?php
class Request {
  protected $data = array();
  
  function __construct($request_data) {
    $this->data = $request_data;
    // @TODO: Test this!
    array_merge($this->data, $_POST, $_GET, $_FILES);
  }
/*  
  public function __set($key, $value)  {
    $this->set_var($key, $value);
  }
*/  
  public function __get($key) {
    return $this->get_var($key);
  } 
/*  
  public function set_var($key, $value) {
    $this->data[$key] = $value;
  }
*/  
  public function get_var($key) {
    if (array_key_exists($key, $this->data)) {
      return $this->data[$key];
    }
  }
  
  public function get_data() {
    return $this->data;
  }
  
  public function params($var = null) {
    if($var === null) {
      return $this->get_data();
    }
    return $this->$var;
    
  }
}
?>