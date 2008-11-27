<?php
class TestOfRegistryClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Registry Class Test');
  }
  
  function setUp() {
    $registry = Registry::instance();
    $registry->save();
  }
  
  function tearDown() {
    $registry = Registry::instance();
    $registry->restore();
  }
  
  function test_basic_access() {
    $registry = Registry::instance();
    
    $this->assertFalse($registry->is_entry('a'), 'unassigned entry should not exist in registry.');
    $this->assertNull($registry->get_entry('a'), 'Trying to get unexistent entry should return null.');
    
    $thing = 'thing';
    $registry->set_entry('a', $thing);
    $this->assertTrue($registry->is_entry('a'));
    $this->assertReference($registry->get_entry('a'), $thing);
  }
  
  function test_dynamic_access() {
    $registry = Registry::instance();
    
    $this->assertNull($registry->a, 'Trying to get unexistent entry should return null. Returned ['.$registry->a.']');
    
    $thing = 'thing';
    $registry->set_entry('a', $thing);
    $this->assertTrue($registry->is_entry('a'));
    $this->assertReference($registry->a, $thing);
  }
  
  function testSingleton() {
    $this->assertReference(Registry::instance(), Registry::instance(), 'Registry should alwys point to a single object');
    $this->assertIsA(Registry::instance(), 'Registry', 'Registry should always be an instance of Registry');
  }
  
  function test_save_and_restore() {
    $registry = Registry::instance();
    $a = 'a';
    $registry->set_entry('a', $a);
    $registry->save();
    $this->assertFalse($registry->is_entry('a'));
    
    $b = 'b';
    $registry->set_entry('a', $b);
    $this->assertReference($registry->get_entry('a'), $b);
    
    $registry->restore();
    $this->assertReference($registry->get_entry('a'), $a);
  }
}  
?>