<?php
class TestOfMigratorClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Migrator Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_filesystem_list_migrations() {
    $migration_root = dirname(__FILE__).'/../test_assets/Migration';
    $expected = array(array(
      'filename'   => '001_create_user_table.php',
      'identifier' => '001',
      'name'       => 'create_user_table',
      'classname'  => 'CreateUserTable'      
    ));
    
    $m = new Migrator(APP_ENV, $migration_root);
    $migration_list = $m->list_filesystem_migrations();
    $this->assertEqual($expected, $migration_list, 'Expected migration list ['.serialize($expected).'] does not fit to the listed migrations ['.serialize($migration_list).']');
  }
  
  function test_schema_info() {
//    $database_adapter = Registry::instance()->database_adapter;
    if(Database::table_exists('schema_info')) {
      Database::drop_table('schema_info');  
    }

    $m = new Migrator(APP_ENV);
    $migration_list = $m->list_executed_migrations();
    
    $this->assertTrue(Database::table_exists('schema_info'), 'Table [schema_info] should exist after calling schema_info_list_migrations()!');
    
    Database::drop_table('schema_info');  
  }
  
  function test_migrate_up_and_down() {
    $migration_root = dirname(__FILE__).'/../test_assets/Migration';
    $m = new Migrator(APP_ENV, $migration_root);
    $m->migrate('up');
    $this->assertTrue(Database::table_exists('tbl_users'), 'User Table should exist after migrating!');
    
    $m->migrate('down');
    $this->assertFalse(Database::table_exists('tbl_users'), 'User Table should not exist after reverting!');
  }
  
  function test_migrate_fails_on_duplicate_identifiers() {
    $migration_root = dirname(__FILE__).'/../test_assets/Migrator/wrong/up';
    $m = new Migrator(APP_ENV, $migration_root);
    
    $this->expectException('Exception', 'Attempting to migrate with double identifiers in the migration root should fail.');  
    $m->migrate('up');
    
    $this->expectException('Exception', 'Attempting to migrate with double identifiers in the migration root should fail.');  
    $m->migrate('down');
  }
}  
?>
