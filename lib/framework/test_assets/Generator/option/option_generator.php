<?php
class OptionGenerator extends Generator {
  public function commands() {
    $source = dirname(__FILE__).'/templates/testfile.txt'; 
    $target = $this->path.'testfile.txt'; 
    $this->file($source, $target);
  }
  
  public $option_mappings = array(0 => 'path');
}
?>