<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');
\Roboframe\Base::enable_module('Cli\Output');
echo "Checking commandline arguments...";

\Generator\Manager\Base::auto_register_framework_generators();

echo "\r\nGenerator...";
if(!isset($argv[1])) {
  echo("Missing first argument [generator]\r\n");
  echo("Available Generators:\r\n");
  $generators = array();
  foreach(\Generator\Manager\Base::generators() as $name => $path) {
    //echo "  {$name}\r\n";
    $generator['name'] = $name;
    $generator['path'] = $path;
    $generators[] = $generator;
  }
  \Cli\Output::array_to_table($generators);
  exit(1);
}
echo "[passed]";

$generator_name = $argv[1];

echo "\r\nInvoking generator [".$generator_name."]...";
// Check if generator exists
if(!$generator = \Generator\Manager\Base::factory($generator_name)) {
  // If not, echo error message and abort
  echo("did not find generator!");
  exit(1);
}
echo "[passed]\r\n";

//Include generator
//require_once($generator_filename);  

$generator_class = "\\Generator\\".\Inflector\Base::camelize($generator_name);

$g = new $generator_class();
$g->map_options(\Generator\Base::extract_generator_options_from_cli($argv));
try {
  $g->run();	
} catch(Exception $e) {
	echo $e->getMessage();
	exit(1);
}
echo "\r\n[done]";