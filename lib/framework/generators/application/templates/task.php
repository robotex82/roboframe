<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');
echo "Checking commandline arguments...";

echo "\r\nTask...";
if(!isset($argv[1])) {
  echo('Missing first argument [task]');
  exit(1);
}
echo "[passed]";

$task_path     = $argv[1];
$exploded_args = explode('::', $task_path);
$task_group    = $exploded_args[0];
$task_name     = $exploded_args[1];
$task_options = (isset($argv[2])) ? array($argv[2]) : array();

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
  $task_group_class::run($task_name, $task_options);
} catch(exception $e) {
  echo "An exception has occurred: ".$e->getmessage()."\r\n";
  echo "Available tasks in [{$task_group}] are:\r\n";
  foreach($task_group_class::available() as $task) {
    echo "  ".$task."\r\n";
  }
}

echo "\r\ndone";
?>