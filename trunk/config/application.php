<?php
// Add your needed libs here

// define logger
try {
  $logger = new Logger();
} catch(Exception $e) {
  die('Error creating Logger Object!');
}

// put application config here
$application['name'] = 'app_name';
?>