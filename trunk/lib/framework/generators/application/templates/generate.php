<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');
echo "Checking commandline arguments...";

echo "\r\nGenerator...";
if(!isset($argv[1])) {
  echo('Missing first argument [generator]');
  exit(1);
}
echo "[passed]";

$generator_name = $argv[1];

echo "\r\nInvoking generator [".$generator_name."]...";
// Check if generator exists
if(!$generator_filename = Generator::search_generator($generator_name)) {
  // If not, echo error message and abort
  echo("did not find generator!");
  exit(1);
}
echo "[passed]\r\n";

//Include generator
//require_once($generator_filename);  

$generator_class = Inflector::camelize($generator_name).'Generator';

$g = new $generator_class();
$g->map_options(Generator::extract_generator_options_from_cli($argv));

$g->run();

echo "\r\ndone";

?>