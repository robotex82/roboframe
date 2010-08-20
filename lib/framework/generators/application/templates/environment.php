<?php
// add your global environment settings here
Roboframe\Base::timezone("Europe/Berlin");
Roboframe\Base::set_logger(new Logger\Text(APP_BASE.'/logs/'.\Roboframe\Base::environment().'_'.date("Y-m-d").'.log'));

// include environment specific settings
include(APP_BASE.'/config/environments/'.\Roboframe\Base::environment().'.php');