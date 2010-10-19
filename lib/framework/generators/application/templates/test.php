<?php
putenv('ROBOFRAME_ENV=test');
error_reporting(E_ALL &~(E_STRICT | E_DEPRECATED));
require_once(dirname(__FILE__).'/../config/bootstrap.php');
Roboframe\Base::enable_module('Cli\Input');
echo "Loaded Roboframe with environment  => [".Roboframe\Base::environment()."]\r\n";

\TaskGroup\Manager\Base::auto_register_framework_task_groups();

if(!isset($argv[1])) {
  echo('Missing first argument [test]');
  exit(1);
}

$task_group    = "test";
$task_name     = $argv[1];
$task_path     = $task_group.'::'.$task_name;
$options       = Cli\Input::parse_arguments(@$argv[2]);
echo "\r\nInvoking task [".$task_path."]...";
// Check if task exists
if(!$task_group_class = \TaskGroup\Manager\Base::find_and_load($task_group)) {
  // If not, echo error message and abort
  echo("did not find task group!\r\n");
  echo("Available Tasks:\r\n");
  foreach(\TaskGroup\Manager\Base::task_groups() as $name => $path) {
    echo "  {$name} => {$path}\r\n";
  }
  exit(1);
}
echo "[passed]\r\n";

//$task_group_class = \Inflector\Base::camelize($task_group).'Tasks';

//$tg = new $task_group_class();
//$g->map_options(Generator::extract_generator_options_from_cli($argv));
try{
  $task_group_class::run($task_name, $options);
} catch(exception $e) {
  echo "An exception has occurred: ".$e->getmessage()."\r\n";
  echo "Available tasks in [{$task_group}] are:\r\n";
  foreach($task_group_class::available() as $task) {
    echo "  ".$task."\r\n";
  }
}

echo "\r\ndone";
?>