<?php
namespace Generator;
class DownloadGenerator extends Base {
  public function commands() {
    $source = 'http://www.example.com'; 
    $target = dirname(__FILE__).'/../target/testdownload.txt'; 
    $this->download($source, $target);
  }
}
?>