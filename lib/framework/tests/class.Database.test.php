<?php
class TestOfDatabaseClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Database Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_load_settings_returns_array() {
    $filename = dirname(__FILE__).'/../test_assets/Database/database_mysql.ini';
    $this->assertTrue(is_array(Database::load_settings(false, $filename)), 'Load Settings should return an array');
  }
   
  function test_settings_array_includes_needed_keys() {
    $filename = dirname(__FILE__).'/../test_assets/Database/database_mysql.ini';
    $settings = Database::load_settings(false, $filename);
    $this->assertTrue(array_key_exists('adapter', $settings), 'Settings should include [adapter]');
    $this->assertTrue(array_key_exists('username', $settings), 'Settings should include [username]');
    $this->assertTrue(array_key_exists('password', $settings), 'Settings should include [password]');
    /*
    foreach($routes as $route) {
      $this->assertTrue(array_key_exists('template', $route), 'Each route should include [url]');
    }
    */
  }
  
  function test_loading_settings_throw_no_exception() {
    $filename = dirname(__FILE__).'/../test_assets/Database/database_mysql.ini';
    $c = Database::load_settings(false, $filename);
    //$this->assertIsA($r, 'router');
  }
  
  function test_incomplete_settings_throw_exception() {
    $wrong_filename = dirname(__FILE__).'/../test_assets/Database/wrong_database.ini';

    $this->expectException('Exception', 'Attempting to load wrong database.ini file should throw an exception');    
    $c = Database::load_settings(false, $wrong_filename);

  }
  
  function test_create_table() {
    $table_name = "test_table";
    $fields = array("a:integer", "b:string:20");
    Database::create_table($table_name, $fields);
    
    $this->assertTrue(Database::get_connection()->execute("SELECT table_name FROM user_tables WHERE table_name='".strtoupper($table_name)."'"), 'Table ['.strtoupper($table_name).'] should exist after create_table()');

    Database::drop_table($table_name);    
//    Database::get_connection()->execute('DROP TABLE '.$table_name);
    $this->assertFalse(Database::table_exists('test_table'), 'Table ['.strtoupper($table_name).'] should not exist after drop_table()');
  }
}  
?>