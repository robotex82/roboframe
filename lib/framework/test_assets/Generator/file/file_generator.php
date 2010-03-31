<?php
class FileGenerator extends \Generator {
  public $option_mappings = array(0 => 'filename');
  
  public function commands() {
    $this->template_root         = dirname(__FILE__).'/templates';
    $this->wrong_filename        = $this->filename;


    $this->template($this->template_root.'/test.template',
                    $this->wrong_filename,
                    array());

  }

}
?>