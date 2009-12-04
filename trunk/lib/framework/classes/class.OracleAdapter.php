<?php
require_once(FRAMEWORK_PATH.'/classes/class.DatabaseAdapter.php');

class OracleAdapter extends DatabaseAdapter {
  private static $datatypes = array(
    'primary_key' => 'number NOT NULL',
    'string'      => 'varchar2',
    'text'        => 'varchar2',
    'integer'     => 'numeric',
    'float'       => 'float',
    'datetime'    => 'date',
    'timestamp'   => 'date (Y-m-d H:i:s)',
    'time'        => 'date (H:i:s)',
    'date'        => 'date (Y-m-d)',
    'binary'      => 'bytea',
    'boolean'     => 'boolean',
    'number'      => 'numeric',
    'inet'        => 'inet'
  );

  public static function get_associated_datatype($datatype) {
    return OracleAdapter::$datatypes[$datatype];
  }

  public static function connect($settings) {
    if(!array_key_exists('adapter', $settings)) {
      throw new Exception('Missing adapter directive in database settings');
    }

    if(!array_key_exists('username', $settings)) {
      throw new Exception('Missing username directive in database settings');
    }

    $connection = ADONewConnection($settings['adapter']);

    if(array_key_exists('debug', $settings)) {
      $connection->debug = false;
    }

    if(array_key_exists('charset', $settings)) {
      $connection->charSet = $settings['charset'];
    }

    if(array_key_exists('nls_lang', $settings)) {
      putenv('NLS_LANG='.$settings['nls_lang']);
    }


    if(!($connection->PConnect($settings['tns_name'], $settings['username'], $settings['password']))) {
        throw new Exception('Connection to database failed!');
    }

    return $connection;
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

  static function table_exists($connection, $table_name) {
    $sql = "SELECT table_name FROM user_tables WHERE table_name='".strtoupper($table_name)."'";
    $result = $connection->getrow($sql);

    if(!isset($result['table_name']) || is_null($result['table_name'])) {
      return false;
    }
    return true;
  }

  static function prefetch_primary_key() {
    return true;
  }

  static function next_sequence_value($sequence_name) {
    $sql = "SELECT ${sequence_name}.nextval id FROM dual";
    return Database::get_connection(getenv('ROBOFRAME_ENV'))->getone($sql);
  }

  static function table_fields($table_name) {
//    $sql = "SELECT COLUMN_NAME AS f, DATA_TYPE AS t, DATA_LENGTH as l "
    $sql = "SELECT lower(COLUMN_NAME) AS fields "
          ."FROM USER_TAB_COLUMNS "
          ."WHERE table_name = '".strtoupper($table_name)."'";
    return Database::get_connection(getenv('ROBOFRAME_ENV'))->getcol($sql);
  }

  static function sequence_name($table_name) {
    return $table_name.'_seq';
  }

  static function create_sequence($sequence_name) {
    $sql = "CREATE SEQUENCE {$sequence_name} START WITH 1 NOCACHE";
    Database::get_connection(getenv('ROBOFRAME_ENV'))->execute($sql);
  }

  static function drop_sequence($sequence_name) {
    $sql = "DROP SEQUENCE {$sequence_name}";
    Database::get_connection(getenv('ROBOFRAME_ENV'))->execute($sql);
  }

  public function upcase_table_name() {
    return true;
  }

  public function quoted_table_name($table_name) {
    return strtoupper($table_name);
  }

  public function quote_value($value) {
    return "'".$value."'";
  }

  public function quote_column($column) {
    return strtoupper($column);
  }
}
?>