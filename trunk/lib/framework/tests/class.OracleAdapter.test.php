<?php
require_once(FRAMEWORK_PATH.'/classes/class.OracleAdapter.php');
class TestOfOracleAdapterClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('OracleAdapter Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
 
  function test_connect_fails_on_missing_adapter() {
    $settings = array();
    $this->expectException('Exception', 'Attempting to connect with missing adapter in settings array should throw an Exception.');  
    $c = OracleAdapter::connect($settings);
  }
  
  function test_connect_fails_on_missing_tns_name() {
    $settings = array('adapter' => 'oci8');
    $this->expectException('Exception', 'Attempting to connect with missing tns name in settings array should throw an Exception.');  
    $c = OracleAdapter::connect($settings);
  }
 
  function test_connect_fails_on_missing_username() {
    $settings = array('adapter' => 'oci8'
                    , 'tns_name' => 'test');
    $this->expectException('Exception', 'Attempting to connect with missing username in settings array should throw an Exception.');  
    $c = OracleAdapter::connect($settings);
  }
  
  function test_connect() {
    $settings = Database::load_settings('production');
    $c = OracleAdapter::connect($settings);
    $this->assertIsA($c, 'ADODB_oci8');
  }
  
  function test_create_table() {
  }
  
  function test_drop_table() {
  }
}  
?>