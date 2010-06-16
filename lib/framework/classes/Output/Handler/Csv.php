<?php
namespace Output\Handler;
class Csv extends Base {
  protected $option_mappings = array(0 => 'filename', 1 => 'disposition');
  
  public function __construct($options) {
    $this->map_options($options);
    $this->set_render_view(true);
  }
  
  public function get_layout_path() {
    return '';
  }
  
  public function before_render($view) {
    #$filename = $view->get_controller_name().'-'.$view->get_action_name().'.csv';
    $filename = $this->options['filename'];
    // set Headers
    header("Content-type: text/csv");
    header("Cache-Control: no-store, no-cache");
    if($this->options['disposition'] == 'inline') {
      header('Content-Disposition: inline; filename="'.$filename.'"');
    } else {
      header('Content-Disposition: attachment; filename="'.$filename.'"');
    }
  }
  
  public function after_render($view) {
  }
}
?>