<?php
namespace TaskGroup;
use \TestSuite;
use \PluginManager;
use \TextReporter;
define('SIMPLE_TEST_PATH', LIBRARY_PATH . '/simpletest');

require_once(SIMPLE_TEST_PATH.'/unit_tester.php');
require_once(SIMPLE_TEST_PATH.'/reporter.php');

class TestTasks extends Base {
  protected static $tasks = array('framework' => ''
                                 ,'models'    => ''
                                 ,'plugins'   => '');
  
  public function framework() {
    $ts = &new TestSuite('Roboframe Framework Tests');
    if(is_dir($roboframe_tests_dir = FRAMEWORK_PATH.'/tests')) {
      foreach(glob($roboframe_tests_dir.'/*.test.php') as $test) {
        $ts->addFile($test);
      }
    }
    $ts->run(new TextReporter());
  }
  
  public function plugins($options) {
    if(isset($options['only'])) {
      $only = explode(',', $options['only']);  
    }
    if(isset($options['except'])) {
      $except = explode(',', $options['except']);  
    }
    
    $ts = &new TestSuite('Plugin tests');
    foreach(PluginManager\Base::find_all() as $plugin) {
      
      if(isset($only) && in_array(basename($plugin), $only)) {
        if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
          //foreach(glob($plugin_tests_dir.'/*.test.php') as $test) {
          foreach(glob($plugin_tests_dir.'/*_test.php') as $test) {
            $ts->addFile($test);
          }
        }
        continue;
      } 
      if(isset($except) && !in_array(basename($plugin), $except)) {
        if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
          //foreach(glob($plugin_tests_dir.'/*.test.php') as $test) {
          foreach(glob($plugin_tests_dir.'/*_test.php') as $test) {
            $ts->addFile($test);
          }
        }
        continue;
      }
      if(!isset($except) && !isset($only)) {
          if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
          //foreach(glob($plugin_tests_dir.'/*.test.php') as $test) {
          foreach(glob($plugin_tests_dir.'/*_test.php') as $test) {
            $ts->addFile($test);
          }
        }
        continue;
      }
      
      /*
      if(is_dir($plugin_tests_dir = $plugin.'/tests')) {
        //foreach(glob($plugin_tests_dir.'/*.test.php') as $test) {
        foreach(glob($plugin_tests_dir.'/*_test.php') as $test) {
          $ts->addFile($test);
        }
      }
      */
    }
    $ts->run(new TextReporter());
  }
  
  public function models($options) {
    if(isset($options['only'])) {
      $only = explode(',', $options['only']);  
    }
    if(isset($options['except'])) {
      $except = explode(',', $options['except']);  
    }

    $ts = &new TestSuite('Model Tests');
    if(is_dir($model_tests_dir = APP_BASE.'/tests/models')) {
      foreach(glob($model_tests_dir.'/*.php') as $test) {
        if(isset($only) && in_array(basename($test, '_test.php'), $only)) {
          $ts->addFile($test);
          continue;
        } 
        if(isset($except) && !in_array(basename($test, '_test.php'), $except)) {
          $ts->addFile($test);
          continue;
        }
        if(!isset($except) && !isset($only)) {
          $ts->addFile($test);
          continue;
        }
      }
    }
    $ts->run(new TextReporter());
  }
}