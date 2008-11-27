<?php
require_once(dirname(__FILE__).'/../test_assets/Model/presence_test.php');
require_once(dirname(__FILE__).'/../test_assets/Model/length_test.php');
require_once(dirname(__FILE__).'/../test_assets/Model/inclusion_test.php');
require_once(dirname(__FILE__).'/../test_assets/Model/format_test.php');
class TestOfModelClass extends UnitTestCase {
  function __construct() {
    $this->UnitTestCase('Model Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_validates_presence_of() {
    $t = new PresenceTest();
    $this->assertFalse($t->validate(), 'Test Model should not validate, if property [name] is empty');
    $t->name = 'foo';
    $this->assertFalse($t->validate(), 'Test Model should not validate, if property [password] is empty');
    $t->password = 'bar';
    $this->assertTrue($t->validate(), 'Test Model should validate, if properties [name] and [password] are not empty');
  }
  
  function test_validates_length_of() {
    $t = new LengthTest();
    $this->assertFalse($t->validate(), 'Test Model should not validate, if property [name] is too short');
    $t->name = 'foobar';
    $t->password = '1';
    $this->assertFalse($t->validate(), 'Test Model should not validate, if property [password] is too short');
    $t->password = 'foobarbaz';
    $this->assertTrue($t->validate(), 'Test Model should validate, if properties [name] and [password] are long enough');
  }
  
  function test_inclusion_of() {
    $t = new InclusionTest();
    $t->name = 'george';
    $this->assertFalse($t->validate(), 'Test Model should not validate, if property [name] is not in [bob,tom,fred]');
    $t->name = 'bob';
    $this->assertTrue($t->validate(), 'Test Model should validate, if property [name] is in [bob, tom, fred]');
  }
  
  function test_format_of() {
    $t = new FormatTest();
    $t->birthdate = '1982-05';
    $this->assertFalse($t->validate(), 'Test Model should not validate, if format of [birthdate] does not match');
    $t->birthdate = '1982-05-12';
    $this->assertTrue($t->validate(), 'Test Model should validate, if format of [birthdate] does match');
  }
}  
?>