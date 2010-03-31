<?php
// include environment specific settings
include(APP_BASE.'/config/environments/'.Roboframe\Base::environment().'.php');

// add your global environment settings here
Roboframe\Base::timezone("Europe/Berlin");
Logger\Text::set_filename(APP_BASE.'/logs/'.date("Y-m-d").'.log');
?>