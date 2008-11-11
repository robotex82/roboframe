<?php
// Add your needed libs here

// define logger
try {
  $logger = new Logger();
} catch(Exception $e) {
  die('Error creating Logger Object!');
}

// start Session
Roboframe::enable_sessions();

// put application config here
$application['name'] = 'app_name';
?>