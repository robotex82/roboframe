<?php
require_once(LIBRARY_PATH.'/adodb/adodb.inc.php');
require_once(LIBRARY_PATH.'/adodb/adodb-exceptions.inc.php');

class Database {
  public static function get_connection($connection_name = false) {
    $settings = Database::load_settings($connection_name);
    
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
   * Loads settings from APP_BASE/config/database.ini
   */
  public static function load_settings($connection_name = false, $filename = false) {
    $connection_name = ($connection_name) ? $connection_name : APP_ENV;
   
    if(!$filename) {
      $filename = APP_BASE.'/config/database.ini';
    }
    
    if(!($settings = parse_ini_file($filename, true))) {
      throw new Exception('Could not read the Database configuration file. '.
                          'Please make sure that there is a proper database.ini file in ['.$filename.'] folder!');
    }
    $env_settings = $settings[$connection_name];

    if(!array_key_exists('adapter', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing adapter directive in connection ['.$connection_name.']');
    }
    
    if(!array_key_exists('username', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing username directive in connection ['.$connection_name.']');
    }
    
    if(!array_key_exists('password', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing password directive in connection ['.$connection_name.']');
    }
    
    return $env_settings;  
  }
  
  public static function connect() {
  }
}
?>