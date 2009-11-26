<?php
require_once('../config/bootstrap.php');

define("VIEW_ROOT", APP_BASE . "/application/views");
define("CONTROLLER_ROOT", APP_BASE . "/application/controllers");
define("LAYOUT_ROOT", APP_BASE . "/application/layouts");

// @TODO Move this to bootstrap.php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VIEW_ROOT);

require_once FRAMEWORK_PATH.'/classes/class.FrontController.php';
require_once CONTROLLER_ROOT.'/application_controller.php';

FrontController::createInstance()->dispatch();
?>