<?php
class Registry {
  private $data;
  
  private function __construct() {
      $this->data = array();
  }

  public static function &instance() {
    static $me;  
    if (is_object($me) == true) {
      return $me;
    }
    $me = new Registry();
    return $me;
  }
  
  public function set_entry($key, &$item) {
      $this->data[0][$key] = &$item;
  }
  
  public function &get_entry($key) {
      return $this->data[0][$key];
  }
  
  public function is_entry($key) {
      return ($this->get_entry($key) !== null);
  }
  
  public function &__get($key) {
    if (array_key_exists($key, $this->data[0])) {
      return $this->data[0][$key];
    } else {
      return null;
    }
  } 
/*
  public function __set($key, $value)  {
    $this->data[0][$key] = $value;
  }
*/  
  public function save() {
    array_unshift($this->data, array());
    if (!count($this->data)) {
      throw new Exception('Registry lost');
    }
  }
  
  public function restore() {
    array_shift($this->data);
  }
  
  public function remove_entry($key) {
    unset($this->data[0][$key]);
  }
}
?>