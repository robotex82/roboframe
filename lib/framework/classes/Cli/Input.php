<?php
namespace Cli;
use \Exception;
class Input {
  /**
   * Parses arguments into key value pairs
   * 
   * Example:
   * \Cli\Input::parse_arguments('foo:bar a:b 1:2');
   *   
   *   => array('foo' => bar, 'a' => 'b', '1' => '2');
   *   
   */
  static public function parse_arguments($arguments) {
    if(!strstr($arguments, ':')) {
      return array();
    }
    $pairs = explode(' ', $arguments);
    
    if(count($pairs) < 1) {
      return array();
    }
    
    $result = array();
    foreach($pairs as $pair) {
      $key_value = explode(':', $pair);
      if(count($key_value) < 2) {
        throw new Exception('Error parsing command line arguments!');
      }
      $result[$key_value[0]] = $key_value[1];
    }
    return $result;
  } 
}