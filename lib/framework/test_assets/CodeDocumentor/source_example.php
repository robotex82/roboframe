<?php
/**
 * This is an example class to be used with the
 * CodeDocumentor Test Class
 */
Class Example {
  /**
   *  This function foos a param
   */
  public function foo($param1) {
    // foo the param
    $result = foo($param1);
    
    // return the fooed param
    return $result;
  }
  
  /**
   *  This method determines the side you are on
   */
  public function which_side($name) {
    // Define array with good people
    $good = array(
        "Luke"
       ,"Yoda" 
       ,"Leia"
    );
    
    // Define array with bad people
    $evil = array(
        "Vader"
       ,"Maul" 
       ,"Palpatine"
    );
    
    // Check whether you are good or not
    if(in_array($name, $good)) {
      // If yes, return "good"
      $return "good";
    }
    
    // Check whether you are evil or not
    if(in_array($name, $evil)) {
    // If yes, return "evil"
      $return "evil";
    }
    
    // Return false, as the name was not found either in good or evil
    return false;
  }
}