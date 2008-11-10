<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');

foreach(Roboframe::list_application_controllers() as $controller) {
  echo $controller."\r\n";
}
?>