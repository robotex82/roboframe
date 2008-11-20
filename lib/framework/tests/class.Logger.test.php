<?php
class TestOfLoggerClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Logger Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_creates_file() {
    $filename = dirname(__FILE__).'/../test_assets/Logger/test.log';  

    @unlink($filename);
    $this->assertFalse(file_exists($filename), 'Log file ['.$filename.'] should not exist!');    
    Logger::write('Test should write this message to the logfile!', $filename);
    $this->assertTrue(file_exists($filename), 'Log file ['.$filename.'] should exist after writing a message to the log');
  }
}  
?>