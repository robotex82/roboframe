<?php
require_once('../config/bootstrap.php');
Roboframe\Base::enable_module('Controller\Front');
require_once \Controller\Base::controller_root().'/application_controller.php';
Controller\Front::createInstance()->dispatch();