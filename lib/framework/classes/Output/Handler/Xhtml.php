<?php
namespace Output\Handler;
class Xhtml extends Base {
  protected $option_mappings = array(0 => 'gzip');
  
  public function __construct($options) {
    $this->options = $this->map_options($options);
    
  }
  
  public function set_layout($layout) {
    $this->options['layout'] = $layout;
  }
  
  public function get_layout_path() {
    return LAYOUT_ROOT.'/'.$this->options['layout'].'.php';
  }
  
  public function enable_gzip() {
    $this->options['gzip'] = true;
  }
  
  public function before_render($view) {
    if($this->options['gzip']) {
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