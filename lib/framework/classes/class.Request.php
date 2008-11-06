<?php
class Request {
  protected $data = array();
  
  function __construct($request_data) {
    $this->data = $request_data;
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
}
?>