<?php
namespace ActiveRecord;

require_once(FRAMEWORK_PATH.'/classes/class.Database.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.PresenceOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.LengthOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.InclusionOfValidator.php');
require_once(FRAMEWORK_PATH.'/classes/validators/class.FormatOfValidator.php');

class Base {
  private $table_prefix = '';
  //private $_primary_key  = 'id';
  private $attributes   = array();
  private $changed_attributes = array();
  private $attribute_columns = array();
  private $database_connection;
  private $database_adapter;

  private function database_adapter() {
    return $this->database_adapter;
  }

  private function sequence_name() {
    $this->table_name().'_seq';
  }

  private function class_name() {
    return get_called_class();
  }

//  private function table_name() {
  public static function table_name() {
    $called_class = get_called_class();
    return $called_class::$table_prefix.\Inflector::tableize(get_called_class());
  }

  private function quoted_table_name() {
    return $this->database_adapter()->quoted_table_name($this->table_name());
  }

  public static function primary_key() {
    $called_class = get_called_class();
    if(isset($called_class::$primary_key)) {
      return $called_class::$primary_key;
    } else {
      return 'id';
    }

    //return $this->_primary_key;
  }

  static function find() {
    $args = func_get_args();
    // Inflect get_called_class to table_name
    //echo ' => => =>'.\Inflector::tableize(get_called_class());
    // find with given params (select * ....)
    // create eobject foreach returned record
    // return collection of objects
    $options = self::extract_options_from_args($args);


    switch($args[0]) {
      case 'first':
        return self::find_first($options);
      case 'all':
        return self::find_all($args);
      default:
        return self::find_by_ids($args, $options);
    }
  }

  private function attributes_with_quotes() {
    $attributes = $this->attributes();
    foreach($attributes as $column => $attribute) {
      $quoted_attributes[$column] = "'".$attribute."'";
    }
    return $quoted_attributes;
  }

  private function attributes() {
    //TODO: Separte attributes from object properties by db columns
    return $this->attributes;
  }

  private function changed_attribute_column_names() {
    return $this->changed_attributes;
  }

  private function reset_changed_attributes() {
    $this->changed_attributes = array();
  }

  private function load_attribute_columns() {
    $this->attribute_columns = $this->database_adapter()->table_fields($this->table_name());
  }

  private function attribute_columns() {
    return $this->attribute_columns;
  }

  private function quoted_attribute_column_names() {
    $result = array();
    foreach($this->attribute_columns() as $attribute_column) {
      $result[] = $this->database_adapter()->quote_column($attribute_column);
    }
    return $result;
  }

  private function quoted_attribute_values() {
    $result = array();
    foreach($this->attribute_columns() as $attribute_column) {
//      echo $this->firstname."\r\n";
      $result[] = $this->database_adapter()->quote_value($this->$attribute_column);
    }
    return $result;
  }

  private static function extract_options_from_args(&$args) {
    if(is_array($args[count($args) - 1])) {
//      unset($args[count($args) - 1]);
      return array_pop($args);
    } else {
      return null;
    }
  }

  private static function find_all($options) {

    $options = \Roboframe::dissect_args($options, 1);
    $called_class = get_called_class();
    $table_name = $called_class::table_name();
    $raw_sql = "SELECT *"
              ." FROM %s";
    if(isset($options['order'])) {
        $raw_sql.=" ORDER BY {$options['order']}";
    }

    if(isset($options['limit'])) {
        $raw_sql.=" LIMIT {$options['limit']}";
    }

    $sql = sprintf($raw_sql
         , $table_name
    );

    $records = \Database::get_connection()->GetArray($sql);
    $objects = array();
    foreach($records as $record) {
      array_push($objects, self::to_object($record));
    }

    return $objects;
  }
/*
  // instance methods and properties
  protected $validators = array();
  protected $errors = array();
*/
  public function __construct($attributes = array()) {
    $this->database_connection = \Database::get_connection(getenv('ROBOFRAME_ENV'));
    $this->database_adapter    = \Database::adapter();

    // init fields
    $this->load_attribute_columns();

    if(method_exists($this, 'init')) {
      $this->init();
    }

    if(!empty($attributes)) {
      $this->attributes = $attributes;
    }
  }

  public function save() {
    // check if this is a new record
    if($this->new_record()) {
      // if yes create record
      return $this->_create();
    } else {
      // else update record
      return $this->_update();

    // end if
    }
  }

  public static function delete($id) {
    $called_class = get_called_class();
    $table_name = $called_class::table_name();

    $sql = "DELETE "  .
           "FROM {$table_name} ".
           "WHERE {$called_class::primary_key()} = {$id}";
    \Database::adapter()->delete($sql, $called_class.' Delete', $called_class::primary_key(), $id);
  }

  public function new_record() {
    // retrieve the primary key attribute name
    //$primary_key = $this->_primary_key;
    $primary_key = self::primary_key();

    // check if primary key is set
    if($this->$primary_key) {
      // return false if it is not set
      return false;
    } else {
      // return true if it is set
      return true;
    }
  }

  private function _create() {
    $database_adapter = $this->database_adapter();

    if($this->id === null && $this->database_adapter()->prefetch_primary_key()) {
      $this->id = $this->database_adapter()->next_sequence_value($this->database_adapter()->sequence_name($this->table_name()));
    }


    $attribute_columns = $this->attribute_columns();
//print_r($this->attribute_columns());
    if(empty($attribute_columns)) {
      $sql = $this->database_adapter()->empty_insert_statement($this->table_name());
    } else {
      $sql = "INSERT INTO {$this->quoted_table_name()} "
            ."(".join($this->quoted_attribute_column_names(), ', ').") "
            ."VALUES (".join($this->quoted_attribute_values(), ', ').")";
    }
//echo $sql;

//    $this->id = $this->database_adapter()->insert($sql, $this->class_name().' Create', $this->primary_key(), $this->id, $this->sequence_name());
    $this->id = $this->database_adapter()->insert($sql, $this->class_name().' Create', self::primary_key(), $this->id, $this->sequence_name());
    $this->reset_changed_attributes();
    return $this->id;
  }

  private function _update() {
    if($this->id === null) {
      throw new Exception("Can't update record without ID");
    }

    $attribute_columns = $this->attribute_columns();
//print_r($this->attribute_columns());
    if(empty($attribute_columns)) {
      $sql = $this->database_adapter()->empty_update_statement($this->table_name());
    } else {
      $sql = "UPDATE {$this->quoted_table_name()} "
            ."SET ";
      foreach($this->changed_attribute_column_names() as $changed_attribute_column_name) {
        $sql.= $this->database_adapter()->quote_column($changed_attribute_column_name)." = ".$this->database_adapter()->quote_value($this->$changed_attribute_column_name).", ";
      }
      $sql = substr($sql, 0, -2);
      $sql.=" WHERE id = ".$this->id;
    }
//echo $sql;

//    $this->id = $this->database_adapter()->update($sql, $this->class_name().' Update', $this->primary_key(), $this->id);
    $this->id = $this->database_adapter()->update($sql, $this->class_name().' Update', self::primary_key(), $this->id);
    $this->reset_changed_attributes();
    return $this->id;
  }

  public function __set($key, $value)  {
    $this->set_attribute($key, $value);
  }
  public function __get($key) {
    return $this->get_attribute($key);
  }

  public function set_attribute($key, $value) {
    $this->attributes[$key] = $value;
    $this->changed_attributes[] = $key;
  }
  public function get_attribute($key) {
    if (array_key_exists($key, $this->attributes)) {
      return $this->attributes[$key];
    }
  }

  public static function to_object($record) {
    $called_class = get_called_class();
    return new $called_class($record);
  }


/*
  public function database_connection($connection_name = false) {
    return Database::get_connection($connection_name);
  }

  protected function validates_presence_of() {
    $args = func_get_args();
    if(!is_string($args[0])) {
      throw new Exception('Expected first param to be a String!');
    }
    $fields = array();
    $fields = explode(',', $args[0]);
    for($i = 0;$i < count($fields); $i++) {
      $fields[$i] = trim($fields[$i]);
    }
    $this->validators[] = new PresenceOfValidator($this, $fields, $args[1]);
  }

  protected function validates_length_of() {
    $args = func_get_args();
    if(count($args) == 0) {
      throw new Exception('Expected to get at least one length definition!');
    }

    foreach($args as $condition) {
      $condition_parts = explode(' is ', $condition);
      $this->validators[] = new LengthOfValidator($this, $condition_parts[0], $condition_parts[1]);
    }
  }

  protected function validates_inclusion_of() {
    $args = func_get_args();
    if(count($args) != 2) {
      throw new Exception('Expected to get two parameters for method Model::validates_inclusion_of()!');
    }
    $field = $args[0];
    $possible_values = explode(', ', $args[1]);
    $this->validators[] = new InclusionOfValidator($this, $field, $possible_values);

  }

  protected function validates_format_of($field, $pattern, $message) {
    $this->validators[] = new FormatOfValidator($this, $field, $pattern, $message);
  }

  public function validate() {
    foreach($this->validators as $validator) {
      if(!$validator->validate()) {
        return false;
      }
    }
    return true;
  }

//  public function add_to_errors($field, $message) {
//    $this->errors[$field] = $message;
//  }

  public function set_error_message_for($field, $message) {
    $this->errors[$field] = $message;
  }

  public function remove_error_message_for($field) {
    unset($this->errors[$field]);
  }

  public function get_error_messages() {
    return $this->errors;
  }

  public function error_message_for($fieldname) {
    if(array_key_exists($fieldname, $this->errors)) {
      return $this->errors[$fieldname];
    }
  }
*/
}
?>