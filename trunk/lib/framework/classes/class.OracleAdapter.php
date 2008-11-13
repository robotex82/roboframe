<?php
class OracleAdapter {
  public static function connect($settings) {
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
}
?>