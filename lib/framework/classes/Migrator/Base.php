<?php
namespace Migrator;
class Base {
  private $database_connection;
  private $environment;
  private $database_adapter;
  private $verbose = true;
  private $migration_root;
  
  public function __construct($environment, $migration_root = false) {
    $this->environment         = $environment;
    $this->database_connection = Database::get_connection($environment);
    $this->database_adapter    = Database::get_adapter_class();
    $this->migration_root      = $migration_root; 
  }
  
  private function database_adapter() {
    return $this->database_adapter;
  }
  
  private function database_connection() {
    return $this->database_connection;
  }
  
  static private function executed_migrations($direction, $limit = false) {}
  static private function unexecuted_migrations($direction, $limit = false) {}
  
  private function create_schema_info_table() {
    $database_adapter = $this->database_adapter();
    $fields = array('identifier:string:3');
    $database_adapter::create_table($this->database_connection, 'schema_info', $fields);
  }
  
  public function list_filesystem_migrations() {
    $migration_root = ($this->migration_root) ? realpath($this->migration_root) : realpath(MIGRATION_ROOT);

    $pattern = "/([0-9]{3})_(.*)\.php/";
    $migrations = array();
    $files = glob($migration_root.'/*.php');

    foreach ($files as $filename) {
      if(preg_match($pattern, basename($filename), $migration_details)) {}
        $migration_details['filename']   = $migration_details[0];
        $migration_details['identifier'] = $migration_details[1];
        $migration_details['name']       = $migration_details[2];
        $migration_details['classname']  = Inflector::camelize($migration_details[2]);
        unset($migration_details[0]);
        unset($migration_details[1]);
        unset($migration_details[2]);
        array_push($migrations, $migration_details);
    }
    return $migrations;
  }
  public function list_executed_migrations() {
    $database_adapter = $this->database_adapter();
    if(!$database_adapter::table_exists($this->database_connection, 'schema_info')) {
      $this->create_schema_info_table();
    }    

    $migrations = $this->database_connection()->getarray('SELECT identifier FROM schema_info');
//print_r($migrations);
    $result = array();
    foreach($migrations as $migration) {
      $result[] = $migration['identifier'];
    }
    return $result;
  }
  
  public function list_unexecuted_migrations() {
    $unexecuted_migrations = array();
    // get executed migrations
    $executed_migrations = $this->list_executed_migrations();
    // get migrations from filesystem (all)  
    $all_migrations = $this->list_filesystem_migrations();
    // loop over all migrations
    foreach($all_migrations as $migration) {
      // if migration exists in executed migrations
      if(!in_array($migration['identifier'], $executed_migrations)) {
        // add migration from the all migrations array
        array_push($unexecuted_migrations, $migration);
      }  
      // end if
    }  
    // end loop
    // return remaining migrations in the all migrations array    
    return $unexecuted_migrations;
  }
  
  public function check_schema_info_table() {
    //$database_adapter = Database::get_adapter_class();
    if(!$$this->database_adapter()->table_exists($this->database_connection, 'schema_info')) {
      $this->create_schema_info_table();
    }
  }
  
  public function migrate($direction, $limit = false) {
    
    switch($direction) {
      case 'up':
        $this->announce('Migrating up');
        return $this->up($limit);
      case 'down':
        $this->announce('Migrating down');
        return $this->down($limit);
      default: break;
    }
  }
  
  private function up($limit) {
    // Make sure all identifiers are unique
    $this->assert_unique_identifiers();
    // loop over unexecuted migrations
    foreach($this->list_unexecuted_migrations() as $migration) {
      // require migration
      require_once($this->migration_root.'/'.$migration['filename']);
      // instanciate migration class
      $migration_class = $migration['classname'];
      $m = new $migration_class($this->environment);
      // invoke migrate('up') on class
      $m->migrate('up');      
    // end loop
    }
  }   
  
  
  private function down($limit) {
    // Make sure all identifiers are unique
    $this->assert_unique_identifiers();
    // loop over executed migrations
    foreach($this->list_executed_migrations() as $migration_identifier) {
      // Get the filename for the migration
      $dir_entries = glob($this->migration_root.'/'.$migration_identifier.'*.php');
      // Make sure there is exactly one migration with this prefix. Otherwise throw an Exception
      if(count($dir_entries) > 1) {
        throw new Exception('Found more than one migration with the prefix ['.$migration_identifier.']! Make sure that there is only one migration with this prefix in your migration root ['.$this->migration_root.']');
      }
      if(count($dir_entries) < 1) {
        throw new Exception('Could not find a migration with the prefix ['.$migration_identifier.']! Make sure that there is a migration with this prefix in your migration root ['.$this->migration_root.']');
      }
      // Get the migration filename
      $migration_filename = basename(realpath($dir_entries[0]));
      // Get the migration classname
      $underscored_migration_class = substr($migration_filename, 4, -4);
      $migration_class = Inflector::camelize($underscored_migration_class);
      // require migration
      require_once($this->migration_root.'/'.$migration_filename);
      // instanciate migration class
      $m = new $migration_class($this->environment);
      // invoke migration ('down') on class
      $m->migrate('down');
    // end loop 
    } 
  }
  
  private function assert_unique_identifiers() {
    $checked_migration_identifiers = array();
    // Loop over migrations on the filesystem
    foreach($this->list_filesystem_migrations() as $migration) {
      // check if the identifier already exists
      if(in_array($migration['identifier'], $checked_migration_identifiers)) {
        // if it exists, throw an exception
        throw new Exception('Found a duplicate identfier ['.$migration['identifier'].'] '
                           .'in the migration root ['.$this->migration_root.']. Make sure that all identifers are unique!');
      } else {
        // else store it        
        $checked_migration_identifiers[] = $migration['identifier'];
      // end if        
      }
    // end loop
    }
    // return true    
    return true;
  }
  
  private function write($text = '') {
    echo ($this->verbose) ? $text : null;
    echo "\r\n";
  }
  
  private function say($message, $subitem = false) {
    $output = ($subitem) ? "  -> " : "-- ";
    $output.= $message;
    $this->write($output);
  }
  
  private function announce($message) {
    $length = strlen($message);
    $this->write($message);
    
    $output = '';
    for($i = 0; $i < $length; $i++) {
      $output.= '=';
    }
    $this->write($output);
  }
}
?>
