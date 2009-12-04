<?php
define('SIMPLE_TEST_PATH', LIBRARY_PATH . '/simpletest');

require_once(SIMPLE_TEST_PATH.'/unit_tester.php');
require_once(SIMPLE_TEST_PATH.'/reporter.php');

class TestTasks extends TaskGroup {
  protected static $tasks = array('framework' =>'');
  
  public function framework() {
    $ts = &new TestSuite('Roboframe Framework Tests');
    if(is_dir($roboframe_tests_dir = FRAMEWORK_PATH.'/tests')) {
      if ($handle = opendir($roboframe_tests_dir)) {
        while (false !== ($roboframe_tests_dir_entry = readdir($handle))) {
          if (substr($roboframe_tests_dir_entry, 0, 1) != ".") {
            $ts->addFile($roboframe_tests_dir.'/'.$roboframe_tests_dir_entry);
          }
        }
      }
    }
    $ts->run(new TextReporter());
  }
}