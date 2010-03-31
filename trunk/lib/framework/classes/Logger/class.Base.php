<?php
namespace Logger;
require_once('class.Text.php');
abstract class Base {
  abstract public function log($message);
  abstract public function debug($message);
  abstract public function info($message);
  abstract public function warn($message);
  abstract public function error($message);
  abstract public function write($message);
}    
?>