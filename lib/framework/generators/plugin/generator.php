<?php
class PluginGenerator extends Generator {
  public $option_mappings = array(0 => 'plugin_name');
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->plugin_path         = $this->app_root.'/plugins/'.Inflector::underscore($this->plugin_name);
    $this->init_filename       = 'init.php';
    $this->main_class_filename = 'class.'.$this->plugin_name.'.php';
    $this->test_class_filename = 'class.'.$this->plugin_name.'.test.php';

    $this->directory($this->plugin_path);
    $this->directory($this->plugin_path.'/lib');
    $this->directory($this->plugin_path.'/tests');
    $this->directory($this->plugin_path.'/test_assets');

    $this->template($this->template_root.'/init.php',
                    $this->plugin_path.'/'.$this->init_filename,
                    array('main_class_filename' => $this->main_class_filename));
    
    $this->template($this->template_root.'/main_class.php',
                    $this->plugin_path.'/lib/'.$this->main_class_filename,
                    array('class_name' => $this->plugin_name));
                    
    $this->template($this->template_root.'/test_class.php',
                    $this->plugin_path.'/tests/'.$this->test_class_filename,
                    array('class_name' => $this->plugin_name));
  }
}
?>