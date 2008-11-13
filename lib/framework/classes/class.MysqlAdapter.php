<?php
class MysqlAdapter {
  public static function connect($settings) {
    $connection = ADONewConnection($settings['adapter']);

    if(!($connection->PConnect($settings['host'], $settings['username'], $settings['password']))) {
        throw new Exception('Connection to database failed!');
    }

    return $connection;
  }
}
?>