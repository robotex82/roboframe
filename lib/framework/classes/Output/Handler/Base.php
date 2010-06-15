<?php
namespace Output\Handler;
abstract class Base {
  protected $options         = array();
  protected $option_mappings = array();
  
  /**
  * Set the option value
  * @param type $option
  */
  public function set_option($option, $value) {
    $this->options[$option] = $value;
  }

  /**
  * Returns the option value.
  * @return type
  */
  public function option($option) {
    return $this->options[$option];
  }
  
  public function set_render_view($value) {
    $this->options['render_view'] = $value;
  }
  
  public function render_view() {
    return $this->options['render_view'];
  }

  public function set_layout($layout) {
    $this->options['layout'] = $layout;
  }  
  
  abstract public function get_layout_path();
  
  public function map_options($options) {
    for($i = 0; $i < count($options); $i++) {
      $this->set_option($this->option_mappings[$i], $options[$i]);
    }
  }

  abstract public function __construct($options);
}
?>