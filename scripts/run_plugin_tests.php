<?php
require_once(dirname(__FILE__).'/../config/bootstrap.php');

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
            if ($plugin_tests_dir_entry != "." && $plugin_tests_dir_entry != "..") {
              $this->addFile($plugin_tests_dir.'/'.$plugin_tests_dir_entry);
            }
          }
        }
      }
    }
  }
}
?>