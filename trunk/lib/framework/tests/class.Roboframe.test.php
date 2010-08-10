<?php
class TestOfRoboframeClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Roboframe Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
/*  
  function test_enable_database() {
    Roboframe\Base::disable_database();
    $this->assertFalse(Registry::instance()->database_connection, 'Database connection should not be available in Registry after call of Roboframe::disable_database()!');
    
    Roboframe\Base::enable_database();
    $this->assertEqual('object', gettype(Registry::instance()->database_connection), 'Database connection in Registry should be of type [Database] after call of Roboframe::enable_database() but is of type ['.gettype(Registry::instance()->database_connection).']!');
  }
*/  
}  
?>