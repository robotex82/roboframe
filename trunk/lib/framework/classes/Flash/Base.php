<?php
namespace Flash;
use ArrayAccess;
use IteratorAggregate;
class Base implements ArrayAccess, IteratorAggregate {
    /* {{{ getIterator IteratorAggregate */
    public function getIterator()
    {
      return new ArrayObject($this);
    }
    /* }}} */
 
   /* {{{ offsetExists ($offset) ArrayAccess */
   public function offsetExists($offset)
   {
     return array_key_exists($offset, $this->data);
   }
   /* }}} */
 
  /* {{{ offsetGet($offset) ArrayAccess */
   public function offsetGet($offset)
   {
     if (array_key_exists($offset, $this->data)) {
          return $this->data[$offset];
     }
   }
   /* }}} */
 
   /* {{{ offsetSet($offset) ArrayAccess */
   public function offsetSet($offset, $value)
   {
       $this->data[$offset] = $value;
   }
   /* }}} */
 
   /* {{{ offsetUnset($offset) ArrayAccess */
   public function offsetUnset($offset)
   {
     if (array_key_exists($offset, $this->data)) {
       unset($this->data[$offset]);
     }
   }
   /* }}} */
 
   public function add($key, $value)
   {
     $this->data[$key] = $value;
   }
 
   public function set($key, $value)
   {
     $this->add($key, $value);
   }
 
   public function get($key)
   {
     return (isset($this->data[$key])) ?
       $this->data[$key] : false;
   }
 
   public function __set($key, $value)
   {
     $this->add($key, $value);
   }
  
  
  protected $data = array();
  protected $keys_to_delete = array();

  public function __construct() {
    if(!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
      $_SESSION['flash'] = array();
    }    
    $this->keys_to_delete = array_keys($_SESSION['flash']);
    $this->data = $_SESSION['flash'];
    unset($_SESSION['flash']);
  }  
  
  public function __destruct() {
    foreach($this->keys_to_delete as $key) {
      $this->offsetUnset($key);
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
  /*
  public function add_data($key, $value)  {
    $this->data[$key] = $value;
  }
  
  public function __call($method, $args) {
    if(isset($args[0])) {
      $this->add_data($method, $args[0]);
    }
  }
  */
  public function notice($message) {
    $this->add('notice', $message);
  }
  
  public function warning($message) {
    $this->add('warning', $message);
  }
}
?>