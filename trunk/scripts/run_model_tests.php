<?php
putenv('ROBOFRAME_ENV=test');
require_once(dirname(__FILE__).'/../config/bootstrap.php');
echo "Loaded Roboframe with environment  => [".getenv('ROBOFRAME_ENV')."]\r\n";

//if (! defined('SIMPLE_TEST')) {
define('SIMPLE_TEST_PATH', APP_BASE . '/lib/simpletest');
//}

require_once(SIMPLE_TEST_PATH.'/unit_tester.php');
require_once(SIMPLE_TEST_PATH.'/reporter.php');
require_once(SIMPLE_TEST_PATH.'/autorun.php');

class AllModelTests extends TestSuite {
  function AllModelTests() {
    $this->TestSuite('All model tests');
    if(is_dir($model_tests_dir = APP_BASE.'/tests/models')) {
      if ($handle = opendir($model_tests_dir)) {
        while (false !== ($model_tests_dir_entry = readdir($handle))) {
          if (substr($model_tests_dir_entry, 0, 1) != ".") {
            $this->addFile($model_tests_dir.'/'.$model_tests_dir_entry);
          }
        }
      }
    }
  }
}
?>