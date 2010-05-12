<?php
namespace Output;
class Csv {
  protected $layout = 'default';
  protected $direct_download = false;
  
  public function __construct() {
  }
  
  public function set_layout($layout) {
    $this->layout = $layout;
  }
  
  public function get_layout_path() {
    return '';
  }
  
  public function direct_download() {
    $this->direct_download = true;
  }
  
  public function before_render($view) {
    $filename = $view->get_controller_name().'-'.$view->get_action_name().'.csv';
    // set Headers
    header("Content-type: text/csv");
    header("Cache-Control: no-store, no-cache");
    if($this->direct_download == true) {
      header('Content-Disposition: attachment; filename="'.$filename.'"');
    } else {
      header('Content-Disposition: inline; filename="'.$filename.'"');
    }
  }
  
  public function after_render($view) {
  }
}
?>