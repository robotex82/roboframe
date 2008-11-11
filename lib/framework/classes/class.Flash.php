<?php
class Flash {
  $data array();
  $keys_to_delete = array();

  public function __construct() {
    if(!is_array($_SESSION['flash'])) {
      $_SESSION['flash'] = array();
    }    
    $this->keys_to_delete = array_keys($_SESSION['flash']);
    $this->data = $_SESSION['flash'];
    unset($_SESSION['flash']);
  }  
  
  public function __destruct() {
    foreach($this->keys_to_delete as $key) {
      unset($this->data[$key]);
    }
    $_SESSION['flash'] = $this->data;
  }
  
  public function __get($key) {
    if (array_key_exists($key, $this->data)) {
      return $this->data[$key];
    }
  } 
  
  public function __set($key, $value)  {
    $this->data[$key] = $value;
  }
}
?>