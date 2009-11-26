<?php
require_once(dirname(__FILE__).'/../test_assets/Generator/test/test_generator.php');
require_once(dirname(__FILE__).'/../test_assets/Generator/template/template_generator.php');
require_once(dirname(__FILE__).'/../test_assets/Generator/option/option_generator.php');
class TestOfGeneratorClass extends UnitTestCase {
  function __construct() {
    $this->UnitTestCase('Generator Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_create_instance() {
    $g = new TestGenerator();
  }
  
  function test_copy_file() {
    $filename = dirname(__FILE__).'/../test_assets/Generator/target/testfile.txt';
    @unlink($filename);

    $g = new TestGenerator();
    $g->run();
    
    $this->assertTrue(file_exists($filename), 'File ['.$filename.'] should exist after running the Test Generator!');
    
    $g->revert(true);
    $g->run();
    
    $this->assertFalse(file_exists($filename), 'File ['.$filename.'] should not exist after reverting the Test Generator!');
  }
  
  function test_revert_changed_file() {
    $filename = dirname(__FILE__).'/../test_assets/Generator/target/testfile.txt';
    @unlink($filename);

    $g = new TestGenerator();
    $g->run();
    
    $this->assertTrue(file_exists($filename), 'File ['.$filename.'] should exist after running the Test Generator!');
    
    $this->assertTrue(file_put_contents($filename, "new content"), 'File ['.$filename.'] should be writable!');
    
    $g->revert(true);
    $g->run();
    
    $this->assertFalse(file_exists($filename), 'File ['.$filename.'] should not exist after reverting the Test Generator!');
  }
  
  function test_template_generator() {
    $first_filename = dirname(__FILE__).'/../test_assets/Generator/target/first.php';
    @unlink($first_filename);  

    $second_filename = dirname(__FILE__).'/../test_assets/Generator/target/second.html';
    @unlink($second_filename);  
    
    $third_filename = dirname(__FILE__).'/../test_assets/Generator/target/third.css';
    @unlink($third_filename);      
      
    $g = new TemplateGenerator();
    $g->run();  
    $this->assertTrue(file_exists($first_filename), 'File ['.$first_filename.'] should exist after running the Template Generator!');
    $pattern = "/Hello Bill/";
    $this->assertPattern($pattern, file_get_contents($first_filename), 'File ['.$first_filename.'] should match pattern ['.$pattern.']!');
    
    $this->assertTrue(file_exists($second_filename), 'File ['.$second_filename.'] should exist after running the Template Generator!');
    $pattern = "/Test site/";
    $this->assertPattern($pattern, file_get_contents($second_filename), 'File ['.$second_filename.'] should match pattern ['.$pattern.']!');
    
    $this->assertTrue(file_exists($third_filename), 'File ['.$third_filename.'] should exist after running the Template Generator!');
    $pattern = "/p\.note/";
    $this->assertPattern($pattern, file_get_contents($third_filename), 'File ['.$third_filename.'] should match pattern ['.$pattern.']!');
    $pattern = "/color: black/";
    $this->assertPattern($pattern, file_get_contents($third_filename), 'File ['.$third_filename.'] should match pattern ['.$pattern.']!');
  }
  
  function test_generator_options() {
    $filename = dirname(__FILE__).'/../test_assets/Generator/target/testfile.txt';
    @unlink($filename);

    $g = new OptionGenerator();
    $g->path = dirname(__FILE__).'/../test_assets/Generator/target/';
    $g->run();
    
    $this->assertTrue(file_exists($filename), 'File ['.$filename.'] should exist after running the Option Generator!');
    
    $g->revert(true);
    $g->run();
    
    $this->assertFalse(file_exists($filename), 'File ['.$filename.'] should not exist after reverting the Option Generator!');
  }
  
  function test_map_options() {
    $filename = dirname(__FILE__).'/../test_assets/Generator/target/testfile.txt';
    @unlink($filename);
    $options = array(dirname(__FILE__).'/../test_assets/Generator/target/');

    $g = new OptionGenerator();
    
    $g->map_options($options);
    $g->run();
    
    $this->assertTrue(file_exists($filename), 'File ['.$filename.'] should exist after running the Option Generator!');
    
    $g->revert(true);
    $g->run();
    
    $this->assertFalse(file_exists($filename), 'File ['.$filename.'] should not exist after reverting the Option Generator!');
  }
}  
?>