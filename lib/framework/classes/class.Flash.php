<?php
class Flash {
  protected $data = array();
  protected $keys_to_delete = array();

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
  /*
  public function __set($key, $value)  {
    $this->data[$key] = $value;
  }
  */
  
  public function add_data($key, $value)  {
    $this->data[$key] = $value;
  }
  
  public function __call($method, $args) {
    if(isset($args[0])) {
      $this->add_data($method, $args[0]);
    }
  }
  
  public function notice($n) {
    $this->add_data('notice', $n);
    
  }
}
?>