<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');
\Roboframe\Base::enable_module('Cli\Output');
echo "Checking commandline arguments...";

\TaskGroup\Manager\Base::auto_register_framework_task_groups();
\TaskGroup\Manager\Base::auto_register_application_task_groups();

echo "\r\nTask...";
if(!isset($argv[1])) {
  echo('Missing first argument [task]');
  echo("Available Tasks:\r\n");
  $task_groups = array();
  foreach(\TaskGroup\Manager\Base::task_groups() as $name => $path) {
    $task_group['name'] = $name;
    $task_group['path'] = $path;
    $task_groups[] = $task_group;
  }
  \Cli\Output::array_to_table($task_groups);
  exit(1);
}
echo "[passed]";

$task_path     = $argv[1];
$exploded_args = explode('::', $task_path);
$task_group    = $exploded_args[0];
$task_name     = @$exploded_args[1];
//$task_options = (isset($argv[2])) ? array($argv[2]) : array();
$task_options = array();
for($i = 2; $i < $argc; $i++) {
  $task_options[] = $argv[$i];
  
}

echo "\r\nInvoking task [".$task_path."]...";
// Check if task exists
if(!$task_group_class = \TaskGroup\Manager\Base::find_and_load($task_group)) {
  // If not, echo error message and abort
  echo("did not find task group!");
  exit(1);
}
echo "[passed]\r\n";

//$task_group_class = "\\TaskGroup\\".\Inflector\Base::camelize($task_group).'Tasks';

//$tg = new $task_group_class();
//$g->map_options(Generator::extract_generator_options_from_cli($argv));
try{
  $return_code = $task_group_class::run($task_name, $task_options);
} catch(exception $e) {
  echo "An exception has occurred: ".$e->getmessage()."\r\n";
  echo "Available tasks in [{$task_group}] are:\r\n";
  $tasks = array();
  foreach($task_group_class::available() as $name) {
    $task['name'] = $name;
    $tasks[] = $task;
  }
  \Cli\Output::array_to_table($tasks);
  exit(1);
}
if($return_code === true) {
  exit(0);
}
echo "\r\ndone";
exit((int)$return_code);
?>