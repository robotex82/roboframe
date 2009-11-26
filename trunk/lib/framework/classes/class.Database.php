<?php
require_once(LIBRARY_PATH.'/adodb/adodb.inc.php');
require_once(LIBRARY_PATH.'/adodb/adodb-exceptions.inc.php');
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
define('ADODB_ASSOC_CASE', 0);

class Database {
  public static function init() {
    $registry = Registry::instance();
    $registry->set_entry('database_connection', Database::get_connection());
  }

  public static function get_adapter_class($connection_name = false) {
     $settings = Database::load_settings($connection_name);
    
    switch ($settings['adapter']) {
    case 'oci8':
        require_once(FRAMEWORK_PATH.'/classes/class.OracleAdapter.php');
        return 'OracleAdapter';
    case 'oci8po':
        require_once(FRAMEWORK_PATH.'/classes/class.OracleAdapter.php');
        return 'OracleAdapter';
    case 'mysql':
        require_once(FRAMEWORK_PATH.'/classes/class.MysqlAdapter.php');
        return 'MysqlAdapter';
    } 
  }
  
  public static function get_connection($connection_name = false) {
    $settings = Database::load_settings($connection_name);
    $adapter_class = Database::get_adapter_class($connection_name);
    return $adapter_class::connect($settings);
    /*
    $settings = Database::load_settings($connection_name);
    
    switch ($settings['adapter']) {
    case 'oci8':
        require_once(FRAMEWORK_PATH.'/classes/class.OracleAdapter.php');
        return OracleAdapter::connect($settings);
    case 'mysql':
        require_once(FRAMEWORK_PATH.'/classes/class.MysqlAdapter.php');
        return MysqlAdapter::connect($settings);
    }
    */
  }
  
  /*
   * Loads settings from APP_BASE/config/database.ini
   */
  public static function load_settings($connection_name = false, $filename = false) {
    $connection_name = ($connection_name) ? $connection_name : APP_ENV;
   
    if($filename === false) {
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
  
  /**
   * Returns an instance of the currenty used Database Adapter
   */
  public static function adapter() {
    $adapter_class = Database::get_adapter_class();
    return new $adapter_class;
  }
  
  public static function create_table($table_name, $fields, $options = array()) {
    $adapter_class = self::get_adapter_class();
    return $adapter_class::create_table(self::get_connection(), $table_name, $fields, $options);
  }
  
  public static function drop_table($table_name) {
    $adapter_class = self::get_adapter_class();
    return $adapter_class::drop_table(self::get_connection(), $table_name);
  }
  
  public static function table_exists($table_name) {
    $adapter_class = self::get_adapter_class();
    return $adapter_class::table_exists(self::get_connection(), $table_name);
  }
  
  public static function drop_table_if_exists($table_name) {
    $adapter_class = self::get_adapter_class();
    if($adapter_class::table_exists(self::get_connection(), $table_name)) {
      return $adapter_class::drop_table(self::get_connection(), $table_name);
    }
    return true;
  }
  
  public static function drop_sequence($sequence_name) {
    $adapter_class = self::get_adapter_class();
    return $adapter_class::drop_sequence($sequence_name);
  }
}
?>