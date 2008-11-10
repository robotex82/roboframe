<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');

$application_actions = array();
foreach(Roboframe::list_application_controller_files() as $controller_dir_entry) {
  echo "\r\n".$controller_dir_entry;
  foreach(Roboframe::list_controller_actions($controller_dir_entry) as $action) {
    echo "\r\n  ".$action.'()';  
  }
}
?>