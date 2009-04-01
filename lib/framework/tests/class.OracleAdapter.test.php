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
  
  function test_table_exists() {
    /*
    $settings = Database::load_settings('production');
    $c = OracleAdapter::connect($settings);
    $table_name = "test_table";
    */
  }
  
  function test_create_table() {
    $settings = Database::load_settings('production');
    $c = OracleAdapter::connect($settings);
    $table_name = "test_table";
    $fields = array("a:integer", "b:string:20");
    
    if(OracleAdapter::table_exists($c, $table_name)) {
      $c->execute('DROP TABLE '.strtoupper($table_name));    
    }

    $this->assertFalse(OracleAdapter::table_exists($c, $table_name), 'Table ['.strtoupper($table_name).'] should not exist before create_table()');
    
    OracleAdapter::create_table($c, $table_name, $fields);
    
    $this->assertTrue($c->execute("SELECT table_name FROM user_tables WHERE table_name='".strtoupper($table_name)."'"), 'Table ['.strtoupper($table_name).'] should exist after create_table()');
    
    $c->execute('DROP TABLE '.$table_name);
  }
  
  function test_drop_table() {
    $settings = Database::load_settings('production');
    $c = OracleAdapter::connect($settings);
    $table_name = "test_table";
    $fields = array("a:integer", "b:string:20");
    
    OracleAdapter::create_table($c, $table_name, $fields);
    //$this->assertTrue($c->execute("SELECT table_name FROM user_tables WHERE table_name='".strtoupper($table_name)."'"), 'Table ['.strtoupper($table_name).'] should exist before drop_table()');
    $this->assertTrue(OracleAdapter::table_exists($c, $table_name), 'Table ['.strtoupper($table_name).'] should exist before drop_table()');
    
    OracleAdapter::drop_table($c, $table_name);
    
    // Check assertion
    //$this->assertFalse($c->execute("SELECT table_name FROM user_tables WHERE table_name='".strtoupper($table_name)."'"), 'Table ['.strtoupper($table_name).'] should not exist after drop_table()');
    $this->assertFalse(OracleAdapter::table_exists($c, $table_name), 'Table ['.strtoupper($table_name).'] should not exist after drop_table()');
  }
  
  function test_data_type_associations() {
    // primary_key => number NOT NULL
    // string => varchar2(255)
    // text => varchar2
    // integer => numeric
    // float => float
    // datetime => date (Y-m-d H:i:s)
    // timestamp => date (Y-m-d H:i:s)
    // time => date (H:i:s)
    // date => date (Y-m-d)
    // binary => bytea
    // boolean => boolean
    // number => numeric
    // inet => inet
    $datatypes = array(
    'primary_key' => 'number NOT NULL',
    'string'      => 'varchar2',
    'text'        => 'varchar2',
    'integer'     => 'numeric',
    'float'       => 'float',
    'datetime'    => 'date',
    'timestamp'   => 'date (Y-m-d H:i:s)',
    'time'        => 'date (H:i:s)',
    'date'        => 'date (Y-m-d)',
    'binary'      => 'bytea',
    'boolean'     => 'boolean',
    'number'      => 'numeric',
    'inet'        => 'inet'
    );
    foreach($datatypes as $native => $associated) {
      $oracle_datatype = OracleAdapter::get_associated_datatype($native);
      $this->assertEqual($associated, $oracle_datatype, 'Associated datatype for PHP datatype ['.$native.'] should be ['.$associated.'] but is ['.$oracle_datatype.']');
    }
  }
}  
?>