<?php
class Inflector {
  static $plural = array(
    '/(quiz)$/i'               => "$1zes",
    '/^(ox)$/i'                => "$1en",
    '/([m|l])ouse$/i'          => "$1ice",
    '/(matr|vert|ind)ix|ex$/i' => "$1ices",
    '/(x|ch|ss|sh)$/i'         => "$1es",
    '/([^aeiouy]|qu)y$/i'      => "$1ies",
    '/([^aeiouy]|qu)ies$/i'    => "$1y",
    '/(hive)$/i'               => "$1s",
    '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
    '/sis$/i'                  => "ses",
    '/([ti])um$/i'             => "$1a",
    '/(buffal|tomat|potat)o$/i'=> "$1oes",
    '/(bu)s$/i'                => "$1ses",
    '/(alias|status)$/i'       => "$1es",
    '/(octop)us$/i'            => "$1i",
    '/(ax|test)is$/i'          => "$1es",
    '/us$/i'                   => "$1es",
    '/s$/i'                    => "s",
    '/$/'                      => "s"
  );

  static $singular = array(
    '/(n)ews$/i'                => "$1ews",
    '/([ti])a$/i'               => "$1um",
    '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
    '/(^analy)ses$/i'           => "$1sis",
    '/([^f])ves$/i'             => "$1fe",
    '/(hive)s$/i'               => "$1",
    '/(tive)s$/i'               => "$1",
    '/([lr])ves$/i'             => "$1f",
    '/([^aeiouy]|qu)ies$/i'     => "$1y",
    '/(s)eries$/i'              => "$1eries",
    '/(m)ovies$/i'              => "$1ovie",
    '/(x|ch|ss|sh)es$/i'        => "$1",
    '/([m|l])ice$/i'            => "$1ouse",
    '/(bus)es$/i'               => "$1",
    '/(o)es$/i'                 => "$1",
    '/(shoe)s$/i'               => "$1",
    '/(cris|ax|test)es$/i'      => "$1is",
    '/(octop|vir)i$/i'          => "$1us",
    '/(alias|status)es$/i'      => "$1",
    '/^(ox)en$/i'               => "$1",
    '/(vert|ind)ices$/i'        => "$1ex",
    '/(matr)ices$/i'            => "$1ix",
    '/(quiz)zes$/i'             => "$1",
    '/(us)es$/i'                => "$1",
    '/s$/i'                     => ""
  );

  static $irregular = array(
    array( 'move',   'moves'    ),
    array( 'sex',    'sexes'    ),
    array( 'child',  'children' ),
    array( 'man',    'men'      ),
    array( 'person', 'people'   )
  );

  static $uncountable = array(
    'sheep',
    'fish',
    'series',
    'species',
    'money',
    'rice',
    'information',
    'equipment'
  );

    
  public static function underscore($camel_cased_word) {
    $return = preg_replace('/::/', '/', $camel_cased_word);
    $return = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', $return);
    $return = preg_replace('/([a-z\d])([A-Z])/', '\1_\2', $return);
    $return = str_replace('-', '_', $return);
    return strtolower($return);
  }
  
  public static function pluralize($string) {
    // save some time in the case that singular and plural are the same
    if(in_array(strtolower($string), self::$uncountable )) {
      return $string;
    }  

    foreach(self::$irregular as $irregular) {
      if (strtolower($string) == $irregular[0]) {
        return $irregular[1];
      }
    }

    foreach(self::$plural as $pattern => $result ) {
      if(preg_match($pattern, $string)) {
        return preg_replace($pattern, $result, $string);
      }
    }
    return $string;
  }

  public static function singularize($string) {
    if(in_array(strtolower($string), self::$uncountable)) {
      return $string;
    }

    // check for irregular singular forms
    foreach(self::$irregular as $irregular) {
      if(strtolower($string) == $irregular[1]) {
        return $irregular[0];
      }
    }

    foreach(self::$singular as $pattern => $result) {
      if(preg_match($pattern, $string)) {
        return preg_replace($pattern, $result, $string);
      }            
    }
    return $string;
  }
  
  public function tableize($class_name) {
    return self::pluralize(self::underscore($class_name));
  }
  
  public function camelize($lower_case_and_underscored_word) {
    // lower_case_and_underscored_word.to_s.gsub(/\/(.?)/) { "::" + $1.upcase }.gsub(/(^|_)(.)/) { $2.upcase }
    //$return = preg_replace('/(^|_)(.)/', strtoupper('\1'), $camel_cased_word); 
    $words = explode('_', $lower_case_and_underscored_word);
    $return = '';
    foreach($words as $word) {
      $return.= ucwords($word);
    }
    return $return;
  }
}
?>