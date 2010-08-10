<?php
namespace Generator;
class TestGenerator extends Base {
  public function commands() {
    $source = dirname(__FILE__).'/templates/testfile.txt'; 
    $target = dirname(__FILE__).'/../target/testfile.txt'; 
    $this->file($source, $target);
  }
}
?>