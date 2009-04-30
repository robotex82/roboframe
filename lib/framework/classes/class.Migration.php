<?php
abstract class Migration {
  private $database_connection;
  private $database_adapter;
  private $environment;
  private $identifier;
  private $name;
  private $verbose = true;

  abstract protected function up();
  abstract protected function down();
  
  public function __construct($environment) {
    $this->environment = $environment;
    $this->database_connection = Database::get_connection($environment);
    $this->database_adapter    = Database::get_adapter_class();
    $this->set_identifier();
    $this->set_name();
  }
  
  private function database_adapter() {
    return $this->database_adapter;
  }
  
  private function database_connection() {
    return $this->database_connection;
  }
  
  private function set_name() {
    $this->name = Inflector::underscore(get_class($this));
  }
  
  private function set_identifier() {
    $r = new ReflectionObject($this);
    $suffix = '_'.Inflector::underscore(get_class($this)).'.php';
    $this->identifier = basename($r->getFileName(), $suffix);
  }

  private function invoke_up() {
    $this->say('up', true);
    $this->up();
    $this->insert_into_schema_info();
  }
  
  private function invoke_down() {
    $this->say('down', true);
    $this->down();
    $this->delete_from_schema_info();
  }
  
  public function migrate($direction) {
    $this->announce('migrating');
    $this->check_schema_info_table();
    switch($direction) {
      case 'up':
        $this->invoke_up();
        break;
      case 'down':
        $this->invoke_down();
        break;
      default: break;
    }
  } 
  
  private function insert_into_schema_info() {
    $raw_sql = "INSERT INTO schema_info "
              ."(identifier) "
              ."VALUES "
              ."('%s')";
              
    $sql = sprintf($raw_sql
      , $this->identifier
    );
//echo $sql;              
    $this->database_connection->execute($sql);
  }
  
  private function delete_from_schema_info() {
    $raw_sql = "DELETE FROM schema_info "
              ."WHERE identifier = '%s'";
              
    $sql = sprintf($raw_sql
      , $this->identifier
    );
              
    $this->database_connection->execute($sql);
  }
  
  private function execute() {}
  
  protected function create_table() {
    $args = func_get_args();
    if(count($args) < 1) {
      throw new Exception('Cannot create table without name in Migration ['.get_class($this).']!');
    }
    
    $table_name = $args[0];
    $this->say('Creating table ['.$table_name.']', true);
    
    if(count($args) < 2) {
      throw new Exception('Cannot create table ['.$table_name.'] without fields in Migration ['.get_class($this).']!');
    }
    
    unset($args[0]);
    $fields = array_reverse($args);
    // TODO: Make ID field optional
    $fields[] = 'id:integer:32:primary_key';
    $fields = array_reverse($fields);
    
    $database_adapter = Database::get_adapter_class();
    $database_adapter::create_table($this->database_connection, $table_name, $fields);  
    // TODO: Make creation of sequence conditional
    if($database_adapter::prefetch_primary_key()) {
      $database_adapter::create_sequence($table_name.'_seq');
    }
  }
  
  protected function drop_table($table_name) {
    $this->say('Dropping table ['.$table_name.']', true);
    $database_adapter = Database::get_adapter_class();
    $database_adapter::drop_table($this->database_connection, $table_name);   
    
    if($database_adapter::prefetch_primary_key()) {
      $database_adapter::drop_sequence($table_name.'_seq');
    }
  }
  
  private function add_index() {}
  private function remove_index() {}
  private function add_field() {}
  private function remove_field() {}  
  
  private function write($text = '') {
    echo ($this->verbose) ? $text : null;
    echo ($this->verbose) ?  "\r\n": null;
  }
  
  private function say($message, $subitem = false) {
    $output = ($subitem) ? "  -> " : "-- ";
    $output.= $message;
    $this->write($output);
  }
  
  private function announce($message) {
    $output = $this->identifier.' '.$this->name.': '.$message;
    $length = strlen($output);
    $this->write($output);
    
    $output = '';
    for($i = 0; $i < $length; $i++) {
      $output.= '=';
    }
    $this->write($output);
  }
  
  public function check_schema_info_table() {
    $database_adapter = Database::get_adapter_class();
    if(!$database_adapter::table_exists($this->database_connection, 'schema_info')) {
      $this->create_schema_info_table();
    }
  }
  
  private function create_schema_info_table() {
    $database_adapter = $this->database_adapter();
    $fields = array('identifier:string:3');
    $database_adapter::create_table($this->database_connection, 'schema_info', $fields);
  }
}
?>
