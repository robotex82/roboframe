<?php
class OutputXhtml {
  protected $layout = 'default';
  protected $gzip_compression = false;
  
  public function __contruct() {
  }
  
  public function set_layout($layout) {
    $this->layout = $layout;
  }
  
  public function get_layout_path() {
    return LAYOUT_ROOT.'/'.$this->layout.'.php';
  }
  
  public function enable_gzip() {
    $this->gzip_compression = true;
  }
  
  public function before_render($view) {
    if($this->gzip_compression) {
      ob_start('ob_gzhandler');
    } else {
      ob_start();
    }
  }
  
  public function after_render($view) {
    ob_flush();
  }
}
?>