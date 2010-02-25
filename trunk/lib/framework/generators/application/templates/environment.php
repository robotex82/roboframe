<?php
// include environment specific settings
/*
if(!defined('APP_ENV')) {
  switch(getenv('ROBOFRAME_ENV')) {
     default:
       die('No environment set. Set "ROBOFRAME_ENV" to "development", "test" or "production" and restart the webserver!');
       
     case 'development':
        define('APP_ENV', 'development');
        //include APP_BASE.'/config/environments/development.php';
     break;
  
     case 'test':
        define('APP_ENV', 'test');
        //include APP_BASE.'/config/environments/test.php';
     break;
     
     case 'production':
        define('APP_ENV', 'production');
        //include APP_BASE.'/config/environments/production.php';
     break;
  }
}
*/
include(APP_BASE.'/config/environments/'.getenv('ROBOFRAME_ENV').'.php');

// add your global environment settings here
Roboframe\Base::timezone("Europe/Berlin");
?>