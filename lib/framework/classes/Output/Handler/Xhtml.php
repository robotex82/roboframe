<?php
namespace Output\Handler;
class Xhtml extends Base {
  protected $option_mappings = array(0 => 'gzip');
  
  public function __construct($options) {
    \View\Base::register_helper(FRAMEWORK_PATH.'/helpers/xhtml.php');
    $this->options = $this->map_options($options);
    $this->set_render_view(true);
    /*
    if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
      $this->set_option('gzip', true);
    };
    */
  }
  
  public function set_layout($layout) {
    $this->options['layout'] = $layout;
  }
  
  public function get_layout_path() {
    return \View\Base::layout_root().'/'.$this->options['layout'].'.php';
  }
  
  public function enable_gzip() {
    $this->options['gzip'] = true;
  }
  
  public function before_render($view) {
    /*
    if($this->option('gzip')) {
      ob_start('ob_gzhandler');
    } else {
      ob_start();
    }
    */
    ob_start();
  }
  
  public function after_render($view) {
    ob_end_flush();
  }
}
?>