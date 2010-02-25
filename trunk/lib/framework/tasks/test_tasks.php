<?php
define('SIMPLE_TEST_PATH', LIBRARY_PATH . '/simpletest');

require_once(SIMPLE_TEST_PATH.'/unit_tester.php');
require_once(SIMPLE_TEST_PATH.'/reporter.php');

class TestTasks extends TaskGroup {
  protected static $tasks = array('framework' =>''
                                 ,'models'    =>''
                                 ,'plugins'   =>'');
  
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
  
  public function plugins() {
    $ts = &new TestSuite('All plugin tests');
    foreach(PluginManager\Base::find_all() as $plugin) {
      if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
        if ($handle = opendir($plugin_tests_dir)) {
          while (false !== ($plugin_tests_dir_entry = readdir($handle))) {
            if (substr($plugin_tests_dir_entry, 0, 1) != ".") {
              $ts->addFile($plugin_tests_dir.'/'.$plugin_tests_dir_entry);
            }
          }
        }
      }
    }
    $ts->run(new TextReporter());
  }
  
  public function models() {
    $ts = &new TestSuite('All model tests');
    if(is_dir($model_tests_dir = APP_BASE.'/tests/models')) {
      if ($handle = opendir($model_tests_dir)) {
        while (false !== ($model_tests_dir_entry = readdir($handle))) {
          if (substr($model_tests_dir_entry, 0, 1) != ".") {
            $ts->addFile($model_tests_dir.'/'.$model_tests_dir_entry);
          }
        }
      }
    }
    $ts->run(new TextReporter());
  }
}