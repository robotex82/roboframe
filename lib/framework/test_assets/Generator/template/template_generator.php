<?php
namespace Generator;
class TemplateGenerator extends Base {
  public function commands() {
    $source = dirname(__FILE__).'/templates/first.php'; 
    $target = dirname(__FILE__).'/../target/first.php'; 
    $options = array('name' => 'Bill');
    $this->template($source, $target, $options);
    
    $source = dirname(__FILE__).'/templates/second.html'; 
    $target = dirname(__FILE__).'/../target/second.html'; 
    $options = array('title' => 'Test site');
    $this->template($source, $target, $options);
    
    $source = dirname(__FILE__).'/templates/third.css'; 
    $target = dirname(__FILE__).'/../target/third.css'; 
    $options = array('class' => 'note', 'color' => 'black');
    $this->template($source, $target, $options);
  }
}
?>