<?php
namespace TaskGroup;
use Exception;
\Roboframe\Base::enable_module('Cli\Output');
class PluginTasks extends Base {
  protected static $tasks = array(
    'install' => ''
   ,'list_active' => '' 
  );

  public function install($options) {
    $plugin_name = $options[0];
    if(\PluginManager\Base::install($plugin_name)) {
      echo "Installed Plugin [".$plugin_name."]";
    } else {
      echo "Could not install Plugin [".$plugin_name."]";
    }
  }

  public function list_active($options) {
    $plugins = array();
    $i = 0;
    foreach(\PluginManager\Base::find_all() as $plugin) {
      $plugins[$i]['name'] = basename($plugin);
      $plugins[$i]['path'] = $plugin;
      $i++;
    }
    \Cli\Output::array_to_table($plugins);
  }
}