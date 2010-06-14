<?php
namespace TaskGroup;
class PluginTasks extends Base {
  protected static $tasks = array('install' => '');

  public function install($options) {
    $plugin_name = $options[0];
    if(PluginManager\Base::install($plugin_name)) {
      echo "Installed Plugin [".$plugin_name."]";
    } else {
      echo "Could not install Plugin [".$plugin_name."]";
    }
  }

}