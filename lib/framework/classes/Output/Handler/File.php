<?php
namespace Output\Handler;
/**
 * Usage:
 * 
 * Put following code in your controller:
 * <code>
 * $this->set_output_format('file|c:/somefile.xls|fred.xls|application/excel');
 * </code>
 * 
 * @author vasquezr
 *
 */
class File extends Base {
  protected $option_mappings = array(0 => 'file', 1 => 'filename', 2 => 'mimetype'); 

  public function __construct($options) {
    $this->map_options($options);
    $this->set_render_view(false);
  }

  public function get_layout_path() {
    return '';
  }

  public function before_render($view) {

    if(empty($this->options['filename'])) {
      $display_name = basename($this->options['file']);
    } else {
      $display_name = $this->options['filename'];
    }
    
    if(empty($this->options['mimetype'])) {
      $mimetype = 'application/octet-stream';
    } else {
      $mimetype = $this->options['mimetype'];
    }
   
    header('Content-disposition: attachment; filename='.$display_name);
    header('Content-type: '.$mimetype);
    readfile($this->options['file']);

  }
  
  public function after_render($view) {
  }
}
?>