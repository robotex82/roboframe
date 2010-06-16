<?php
namespace Output\Handler;
use \FPDF;
define('FPDF_FONTPATH', LIBRARY_PATH.'/fpdf/font/');
require(LIBRARY_PATH.'/fpdf/fpdf.php');
class Pdf extends Base {
  protected $option_mappings = array(0 => 'filename', 1 => 'disposition');
  
  public function __construct($options) {
    $this->map_options($options);
    $this->set_render_view(true);
  }

  public function get_layout_path() {
    return '';
  }

  public function before_render($view) {
    ob_start();
    $view->add_view_data('pdf', new FPDF());
  }
  
  public function after_render($view) {
    $view->get_view_item('pdf')->Output($this->options['filename'], substr($this->options['disposition'], 0, 1));
    ob_flush();
  }
}
?>