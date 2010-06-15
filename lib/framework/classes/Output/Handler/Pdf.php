<?php
namespace Output;
define('FPDF_FONTPATH', LIBRARY_PATH.'/fpdf/font/');
require(LIBRARY_PATH.'/fpdf/fpdf.php');
class Pdf {
  protected $layout = 'default';
  protected $direct_download = false;
  
  public function __contruct() {
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
    $view->add_view_data('pdf', new FPDF());
  }
  
  public function after_render($view) {
    $filename = $view->get_controller_name().'-'.$view->get_action_name().'.pdf';
    // set Headers
    if($this->direct_download == true) {
      $view->get_view_item('pdf')->Output($filename, 'D');
    } else {
      $view->get_view_item('pdf')->Output($filename, 'I');
    }
  }
}
?>