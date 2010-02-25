<?php
namespace PluginManager;
class Base {
  /*
   * Initializes all Plugins by calling their init.php script
   */
  static public function initialize_all() {
    $plugins = \PluginManager\Base::find_all();
    foreach($plugins as $plugin) {
      require_once($plugin.'/init.php');
    }
  }
  
  /*
   * Returns an array of all plugin names
   */  
  static public function find_all() {
    $plugin_dir = APP_BASE.'/plugins';
    if(!is_dir($plugin_dir)) {
      throw new Exception('The Plugins directory ['.$plugin_dir.'] is missing!');
    }
    
    if ($handle = opendir($plugin_dir)) {
      $plugins = array();
      while (false !== ($plugin_dir_entry = readdir($handle))) {
        if ($plugin_dir_entry != "." && $plugin_dir_entry != "..") {
          $plugin_subdir = $plugin_dir.'/'.$plugin_dir_entry;
          if(is_dir($plugin_subdir) && file_exists($plugin_subdir.'/init.php')) {
             //require_once($plugin_subdir.'/init.php');
             $plugins[] = $plugin_subdir;
          }
        }
      }
      closedir($handle);
    }
    return $plugins;
  }
}

?>