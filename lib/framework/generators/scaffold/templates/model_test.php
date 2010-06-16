<|?php
class TestOf<?= $model_class ?>Class extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('<?= $model_class ?> Test');
  }
  
  function __destruct() {
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_not_implemented() {
    $this->assertTrue(false, '<?= $model_class ?> Test not implemented!');
  }
} 
 
?>