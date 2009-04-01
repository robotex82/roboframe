<?php
class TestOfMigrationClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Migration Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }

  
  function test_up() {
    Database::drop_table_if_exists('tbl_users');
    Database::drop_table_if_exists('schema_info');
     
    require_once(dirname(__FILE__).'/../test_assets/Migration/001_create_user_table.php');
    
    $m = new CreateUserTable(APP_ENV);
    $m->migrate('up');
    $this->assertTrue(Database::table_exists('tbl_users'), 'Table [tbl_users] should exist after migrating up!');
    
    // Check Schema Info
    
    $m = new CreateUserTable(APP_ENV);
    $m->migrate('down');
    $this->assertFalse(Database::table_exists('tbl_users'), 'Table [tbl_users] should not exist after migrating down!');
    
    /*
    $m = new Migration(APP_ENV);
    $m->migrate('up');
    $this->assertTrue(Database::table_exists('tbl_users'), 'Table [tbl_users] should exist after migrating up!');
    */
  }
  
  function test_down() {
    /*
    $m = new Migration(APP_ENV);
    $m->migrate('down');
    $this->assertFalse(Database::table_exists('tbl_users'), 'Table [tbl_users] should not exist after migrating down!');
    */
  }
  
  function test_create_table() {
  }
  
  function test_drop_table() {
  }
}  
?>
