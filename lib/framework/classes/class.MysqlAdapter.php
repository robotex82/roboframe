<?php
class MysqlAdapter {
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
}
?>