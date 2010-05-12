<?php
namespace DatabaseAdapter;
abstract class Base {
  public function connection() {
    return \Database\Base::get_connection(\Roboframe\Base::environment());
  }

  public function empty_insert_statement($table_name) {
    $table_name = ($this->upcase_table_name()) ? strtoupper($table_name) : $table_name;
    return "INSERT INTO {$table_name} VALUES (DEFAULT)";
  }

  public function insert($sql, $name = null, $pk = null, $id_value = null, $sequence_name = null) {
    $this->connection()->execute($sql);
    return $id_value;
  }

  public function update($sql, $name = null, $pk = null, $id_value = null) {
    $this->connection()->execute($sql);
    return $id_value;
  }
  
  public function delete($sql, $name = null, $pk = null, $id_value = null) {
    $this->connection()->execute($sql);
    return $id_value;
  }

  abstract function upcase_table_name();
  abstract function quoted_table_name($table_name);
  abstract function quote_value($value);
  abstract function quote_column($column);


}
