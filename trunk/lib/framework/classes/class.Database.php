<?php
require_once(LIBRARY_PATH.'/adodb/adodb.inc.php');
require_once(LIBRARY_PATH.'/adodb/adodb-exceptions.inc.php');

class Database {
  public static function get_connection() {
    $settings = Database::load_settings();
    
    switch ($settings['adapter']) {
    case 'oci8':
        require_once(FRAMEWORK_PATH.'/classes/class.OracleAdapter.php');
        return OracleAdapter::connect($settings);
    case 'mysql':
        require_once(FRAMEWORK_PATH.'/classes/class.MysqlAdapter.php');
        return MysqlAdapter::connect($settings);
    }
  }
  
  /*
   * Loads settings from APP_BASE/config/content_server.ini
   */
  public static function load_settings() {
    if(!($settings = parse_ini_file(APP_BASE.'/config/database.ini', true))) {
      throw new Exception('Could not read the Database configuration file. '.
                          'Please make sure that there is a proper database.ini file in the '.APP_BASE.'/config folder!');
    }
    $env_settings = $settings[APP_ENV];
    return $env_settings;  
  }
  
  public static function connect() {
  }
}
?>