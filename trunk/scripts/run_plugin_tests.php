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

class AllPluginTests extends TestSuite {
  function AllPluginTests() {
    $this->TestSuite('All plugin tests');
    foreach(PluginManager::find_all() as $plugin) {
      if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
        if ($handle = opendir($plugin_tests_dir)) {
          while (false !== ($plugin_tests_dir_entry = readdir($handle))) {
            if (substr($plugin_tests_dir_entry, 0, 1) != ".") {
              $this->addFile($plugin_tests_dir.'/'.$plugin_tests_dir_entry);
            }
          }
        }
      }
    }
  }
}
?>