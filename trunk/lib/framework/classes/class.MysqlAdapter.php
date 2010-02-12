<?php
require_once(FRAMEWORK_PATH.'/classes/class.DatabaseAdapter.php');

class MysqlAdapter extends DatabaseAdapter {
  private static $datatypes = array(
     'primary_key' => 'int(11) DEFAULT NULL auto_increment PRIMARY KEY'
    ,'string'      => 'varchar'
    ,'text'        => 'text'
    ,'integer'     => 'int'
    ,'float'       => 'float'
    ,'decimal'     => 'decimal'
    ,'datetime'    => 'datetime'
    ,'timestamp'   => 'datetime'
    ,'time'        => 'time'
    ,'date'        => 'date'
    ,'binary'      => 'blob'
    ,'boolean'     => 'boolean'
//    'number'      => 'numeric',
//    'inet'        => 'inet'
  );

  public static function get_associated_datatype($datatype) {
    return MysqlAdapter::$datatypes[$datatype];
  }
  
  public static function connect($settings) {
    
/*
    if(!($connection->PConnect($settings['host'], $settings['username'], $settings['password'], $settings['database']))) {
      throw new Exception('Connection to database failed!');
    }
*/

    
    try { 
      $connection = ADONewConnection($settings['adapter']);
      $connection->PConnect($settings['host'], $settings['username'], $settings['password'], $settings['database']);
    } catch (exception $e) { 
      echo 'Connection to database failed!';
      var_dump($e->getMessage()); 
      adodb_backtrace($e->gettrace());
      die();
    } 
    
    if(isset($settings['fetchmode']) && $settings['fetchmode'] == 'column') {
      $connection->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    
    if(isset($settings['fetchmode']) && $settings['fetchmode'] == 'numeric') {
      $connection->SetFetchMode(ADODB_FETCH_NUM);
    }
    
    return $connection;
  }
  
  static function table_exists($connection, $table_name) {
    // Build SQL Statement
    $sql = "SHOW tables like '{$table_name}'";
    // Execute SQL Statement
    $result = $connection->getarray($sql);

    // Table exists if the resultset returns 1 entry
    if(count($result) == 1) {
      // return true
      return true;
    }
    // Return false, if the result set has more or less then 1 entry
    return false;
  }
  
  static function prefetch_primary_key() {
    return false;
  }
  
  static function table_fields($table_name) {
    $sql = "SHOW columns FROM {$table_name}";
    $result = Database::get_connection(getenv('ROBOFRAME_ENV'))->getarray($sql);
    $return = array();
    foreach($result as $row) {
      $return[] = $row['Field'];
    }

    return $return;
  }

  static function create_table($connection, $table_name, $fields, $options = array()) {
    $sql = "CREATE TABLE ".$table_name." (";

    $field_count = count($fields);
    $i = 0;
    foreach($fields as $field) {
      $field_details = explode(":", $field);

      $sql.= $field_details[0]." ".self::get_associated_datatype($field_details[1]);
      if(isset($field_details[2])) {
        $sql.= "(".$field_details[2].")";
      }
      if($i < $field_count - 1) {
        $sql.= ", ";
      }
      $i++;
    }

    $sql.= ")";
    $connection->execute($sql);
  }
  
  static function drop_table($connection, $table_name) {
    $sql = "DROP TABLE ".$table_name;
    $connection->execute($sql);
  }
  
  public function upcase_table_name() {
    return false;
  }
  
  public function quoted_table_name($table_name) {
    return $table_name;
  }

  public function quote_column($column) {
    return $column;
  }
  
  public function quote_value($value) {
    return "'".$value."'";
  }
}
?>