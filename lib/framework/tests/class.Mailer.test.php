<?php
require_once(dirname(__FILE__).'/../test_assets/Mailer/test_mailer.php');
class TestOfMailerClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Mailer Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_load_settings_returns_array() {
    $filename = dirname(__FILE__).'/../test_assets/Mailer/good_mailer.ini';
    $this->assertTrue(is_array(Mailer::load_settings($filename)), 'Load Settings should return an array');
  }
   
  function test_settings_array_includes_needed_keys() {
    $filename = dirname(__FILE__).'/../test_assets/Mailer/good_mailer.ini';
    $settings = Mailer::load_settings($filename);
    $this->assertTrue(array_key_exists('delivery_method', $settings), 'Settings should include [delivery_method]');
    $this->assertTrue(array_key_exists('host', $settings), 'Settings should include [host]');
    $this->assertTrue(array_key_exists('from_address', $settings), 'Settings should include [from_address]');
    /*
    foreach($routes as $route) {
      $this->assertTrue(array_key_exists('template', $route), 'Each route should include [url]');
    }
    */
  }
  
  function test_loading_settings_throw_no_exception() {
    $filename = dirname(__FILE__).'/../test_assets/Mailer/good_mailer.ini';
    $m = Mailer::load_settings($filename);
    //$this->assertIsA($r, 'router');
  }
  
  function test_incomplete_settings_throw_exception() {
    $wrong_filename = dirname(__FILE__).'/../test_assets/Mailer/wrong_mailer.ini';

    $this->expectException('Exception', 'Attempting to load wrong mailer.ini.ini file should throw an exception');    
    $m = Mailer::load_settings($wrong_filename);

  }

  function test_deliver() {
    $view_root = realpath(dirname(__FILE__).'/../test_assets/Mailer/views');

    $tm = new TestMailer();
    $tm->set_view_root($view_root);
    $this->assertTrue($tm->deliver_user_notification(), 'Delivering [user_notification] should return true');
  } 
  
  function test_deliver_with_attachments() {
    $view_root = realpath(dirname(__FILE__).'/../test_assets/Mailer/views');
  
    $tm = new TestMailer();
    $tm->set_view_root($view_root);
    $files = array();
    $files[] = dirname(__FILE__).'/../test_assets/Mailer/attachment_1.txt';
    $files[] = dirname(__FILE__).'/../test_assets/Mailer/attachment_2.pdf';
    foreach($files as $file) {
    $this->assertTrue(is_readable($file), 'File ['.$file.'] should exist!');
    }
    $this->assertTrue($tm->deliver_user_notification_with_attachments($files), 'Delivering [user_notification] with attachments should return true');
  } 
  
  function test_view_rendering() {
    $view_root = realpath(dirname(__FILE__).'/../test_assets/Mailer/views');
    
    $tm = new TestMailer();    
    $tm->set_view_root($view_root);
    $test_content = "Test content from the View";
    $rendered_content = $tm->render_test_mail();
    $this->assertEqual($rendered_content, $test_content, 'Rendering test mail should return the test content ['.$test_content.'] but returned ['.$rendered_content.']');
    
    $tm = new TestMailer();    
    $tm->set_view_root($view_root);
    $test_content = "Hello bob!";
    $rendered_content = $tm->render_test_dynamic_mail();
    $this->assertEqual($rendered_content, $test_content, 'Rendering dynamic test mail should return the test content ['.$test_content.'] but returned ['.$rendered_content.']');
  }
}  
?>