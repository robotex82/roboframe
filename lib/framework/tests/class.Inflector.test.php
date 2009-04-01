<?php
class TestOfInflectorClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Inflector Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_underscore() {
    $camel_cased_word = 'ActiveRecord';
    $underscored_word = 'active_record';
    
    $this->assertEqual($underscored_word, Inflector::underscore($camel_cased_word), 'Underscored ['.$camel_cased_word.'] should be ['.$underscored_word.'] but is ['.Inflector::underscore($camel_cased_word).']');
  }

  function test_pluralize() {
    $singular = 'post';
    $plural   = 'posts';
    
    $this->assertEqual($plural, Inflector::pluralize($singular), 'Pluralized ['.$singular.'] should be ['.$plural.'] but is ['.Inflector::pluralize($singular).']');
  }
  
  function test_tableize() {
    $camel_cased = 'LegacyUser';
    $tableized   = 'legacy_users';
    
    $this->assertEqual($tableized, Inflector::tableize($camel_cased), 'Tableized ['.$camel_cased.'] should be ['.$tableized.'] but is ['.Inflector::tableize($camel_cased).']');
  }
  
  function test_camelize() {
    $lower_case_and_underscored_word   = 'legacy_user';
    $camel_cased = 'LegacyUser';
        
    $this->assertEqual($camel_cased, Inflector::camelize($lower_case_and_underscored_word), 'Camelized ['.$lower_case_and_underscored_word.'] should be ['.$camel_cased.'] but is ['.Inflector::camelize($lower_case_and_underscored_word).']');
  }
}  
?>