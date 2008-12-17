<?php
define('APP_ENV', 'test');
require_once(dirname(__FILE__).'/../config/bootstrap.php');
echo "Loaded Roboframe with environment  => [".APP_ENV."]\r\n";

//if (! defined('SIMPLE_TEST')) {
define('SIMPLE_TEST_PATH', APP_BASE . '/lib/simpletest');
//}

require_once(SIMPLE_TEST_PATH.'/unit_tester.php');
require_once(SIMPLE_TEST_PATH.'/reporter.php');
require_once(SIMPLE_TEST_PATH.'/autorun.php');

class AllRoboframeTests extends TestSuite {
  function AllRoboframeTests() {
    $this->TestSuite('All roboframe tests');

    if(is_dir($roboframe_tests_dir = FRAMEWORK_PATH.'/tests')) {
      if ($handle = opendir($roboframe_tests_dir)) {
        while (false !== ($roboframe_tests_dir_entry = readdir($handle))) {
          //if ($roboframe_tests_dir_entry != "." && $roboframe_tests_dir_entry != "..") {
          if (substr($roboframe_tests_dir_entry, 0, 1) != ".") {
            $this->addFile($roboframe_tests_dir.'/'.$roboframe_tests_dir_entry);
          }
        }
      }
    }
  }
}
?>