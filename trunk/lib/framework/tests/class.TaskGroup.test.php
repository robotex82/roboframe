<?php
require_once(dirname(__FILE__).'/../test_assets/Tasks/greeter_tasks.php');
require_once(dirname(__FILE__).'/../test_assets/Tasks/chain_tasks.php');

class TestOfTaskGroupClass extends UnitTestCase {
  function __construct() {
    $this->UnitTestCase('Task Group Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_create_instance() {
    //$g = new TestGenerator();
  }
  
  function test_greeter() {
    
    $options = array('name' => 'John');

    ob_start();
    \TaskGroup\GreeterTasks::run('hello', $options);
    $output = ob_get_clean();

    
    $pattern = "/Hello John/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');

    ob_start();
    \TaskGroup\GreeterTasks::run('how_are_you', array(), array(0 => $options));
    $output = ob_get_clean();
    
    $pattern = "/Hello John/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
    
    $pattern = "/How are you/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
  }
  
  function test_chain() {
    $options = array('name' => 'Bob', 'model' => 'Ford Mustang');
    $pre_options = array(0 => array('name' => 'Bob', 'amount' => 15000)
                        ,1 => array('name' => 'Bob', 'amount' => 3000)
                        ,2 => array('name' => 'Bob', 'hours' => 8) 
                        ,3 => array('name' => 'Bob'));
  
    ob_start();
    \TaskGroup\ChainTasks::run('buy_car', $options, $pre_options);
    $output = ob_get_clean();
    
    $pattern = "/Bob stands up/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
    
    $pattern = "/Bob works for 8 hours/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
    
    $pattern = "/Bob gets salary \(3000\)/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
    
    $pattern = "/Bob withdraws 15000 euros/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');

    $pattern = "/Bob buys a fancy new Ford Mustang car/";
    $this->assertPattern($pattern, $output, 'Output ['.$output.'] should match pattern ['.$pattern.']!');
  }
  
  function test_unknown_task_throws_exception() {
    $this->expectException('Exception', 'Attempting to run an unknown task should throw an Exception.');  
    \TaskGroup\GreeterTasks::run('invalid_taskname');
  }
}  
?>