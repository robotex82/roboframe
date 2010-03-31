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
    $l = new Logger\Text();
    $l->set_filename($filename);

    @unlink($filename);
    $this->assertFalse(file_exists($filename), 'Log file ['.$filename.'] should not exist!');    
    $l->write('Test should write this message to the logfile!', $filename);
    $this->assertTrue(file_exists($filename), 'Log file ['.$filename.'] should exist after writing a message to the log');
  }
  
  function test_missing_folder_throws_exception() {
    $filename = dirname(__FILE__).'/../test_assets/Logger/missing_dir/test.log';  
    $l = new Logger\Text();
    $l->set_filename($filename);
    
    $this->assertFalse(is_dir(dirname($filename)), 'Directory ['.dirname($filename).'] should not exist!'); 
    $this->expectException('Exception', 'Attempting to write log file to missing folder should throw an Exception!');    
    $l->write('Test should not write this message!', $filename);
  }
}  
?>