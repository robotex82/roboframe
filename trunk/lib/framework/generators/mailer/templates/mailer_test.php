<|?php
class TestOf<?= $class_name ?>Class extends UnitTestCase {
  function __construct() {
    $this->UnitTestCase('<?= $class_name ?> Test');
  }
  
  function __destruct() {
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_not_implemented() {
    $this->assertTrue(false, '<?= $class_name ?> Test not implemented!');
  }
} 
 
?>