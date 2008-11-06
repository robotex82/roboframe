<?php
Class Registry {
  private $_objects = array();


  public function set($name, &$object) {
    $this->_objects[$name] =& $object;
  }

  public function &get($name) {
    return $this->_objects[$name];
  }
        
  function &getInstance() {
    static $me;  
    if (is_object($me) == true) {
      return $me;
    }
    $me = new Registry;
    return $me;
  }
}

?>