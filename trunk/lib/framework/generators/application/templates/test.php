<?php
putenv('ROBOFRAME_ENV=test');
require_once(dirname(__FILE__).'/../config/bootstrap.php');
echo "Loaded Roboframe with environment  => [".getenv('ROBOFRAME_ENV')."]\r\n";

if(!isset($argv[1])) {
  echo('Missing first argument [test]');
  exit(1);
}

$task_group    = "Test";
$task_name     = $argv[1];
$task_path     = $task_group.'::'.$task_name;

echo "\r\nInvoking task [".$task_path."]...";
// Check if task exists
if(!$task_group_filename = TaskGroup::search($task_group)) {
  // If not, echo error message and abort
  echo("did not find task group!");
  exit(1);
}
echo "[passed]\r\n";

$task_group_class = Inflector::camelize($task_group).'Tasks';

//$tg = new $task_group_class();
//$g->map_options(Generator::extract_generator_options_from_cli($argv));
try{
  $task_group_class::run($task_name);
} catch(exception $e) {
  echo "An exception has occurred: ".$e->getmessage()."\r\n";
  echo "Available tasks in [{$task_group}] are:\r\n";
  foreach($task_group_class::available() as $task) {
    echo "  ".$task."\r\n";
  }
}

echo "\r\ndone";
?>