<?php
class DownloadGenerator extends Generator {
  public function commands() {
    $source = 'http://www.example.com'; 
    $target = dirname(__FILE__).'/../target/testdownload.txt'; 
    $this->download($source, $target);
  }
}
?>