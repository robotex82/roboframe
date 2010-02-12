<?php
require_once(FRAMEWORK_PATH.'/classes/CodeDocumentor/class.Base.php');
require_once(FRAMEWORK_PATH.'/classes/CodeDocumentor/class.TextOutput.php');
//require_once();

class TestOfCodeDocumentorClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('CodeDocumentor Class Test');
  }

  function setUp() {
  }

  function tearDown() {
  }
  
  function test_extract_comments_from_file() {
    $filename = dirname(__FILE__).'/../test_assets/CodeDocumentor/source_example.php';
    $output_filename = dirname($filename)."/".basename($filename, ".php").".txt";
    
    $doc = CodeDocumentor\Base::extract_comments_from_file($filename);

    file_put_contents($output_filename, join("\r\n", CodeDocumentor\TextOutput::generate_documentation($filename, $doc)));
    echo ">>>>>".$output_filename."<<<<<<";
  }

}
?>