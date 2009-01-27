<?php
class OutputFile {
  protected $layout = 'default';
  protected $file;
  protected $filename;
  protected $mimetype;
  
  public function __construct() {
  }
  
  public function set_layout($layout) {
    $this->layout = $layout;
  }
  
  public function set_file($file) {
    $this->file = $file;
  }
  
  public function set_filename($filename) {
    $this->filename = $filename;
  }
  
  public function set_mimetype($mimetype) {
    $this->mimetype = $mimetype;
  }
  
  public function get_layout_path() {
    return '';
  }
  
  public function before_render($view) {
    // set Headers
//    header("Content-type: ".$this->mimetype);
//    header("Cache-Control: no-store, no-cache");
    if(empty($this->filename)) {
      $display_name = basename($this->file);
    } else {
      $display_name = $this->filename;
    }
    
    if(empty($this->mimetype)) {
      $mimetype = 'application/octet-stream';
    } else {
      $mimetype = $this->mimetype;
    }
/*    
    echo $display_name;
    echo $mimetype;
    echo $this->file;
*/    
    header('Content-disposition: attachment; filename='.$display_name);
    header('Content-type: '.$mimetype);
    readfile($this->file);

  }
  
  public function after_render($view) {
  }
}
?>