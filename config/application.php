<?php
// Add your needed libs here
require_once('dss/dss.includes.php');

// define logger
try {
  $logger = new dss_ErrorAndLogHandler();
} catch(Exception $e) {
  die('Error creating Logger Object!');
}

// put application config here
$application['name'] = 'Document Storage System';
?>