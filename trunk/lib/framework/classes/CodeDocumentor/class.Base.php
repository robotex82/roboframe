<?php
namespace CodeDocumentor;

class Base {
  public static function tokenize_file($filename) {
    $source = file_get_contents($filename);
    return token_get_all($source);   
  }
  
  public static function extract_comments($tokens) {
    // Initialize the result array
    $result = array();
    // Initialize the class counter
    $class_counter = -1;
    // Initialize the method counter
    $method_counter = 0;
    
    // Loop through tokens
    for($i = 0 ; $i < count($tokens); $i++) {
      // Check if the given token is a simple 1-char token
      if (is_string($tokens[$i])) {
        // If yes, ignore it
      } else {
        // separte the given token into id and text
        list($id, $text, $line) = $tokens[$i];
        // Switch the id
        switch ($id) { 
          // if it is a class
          case T_CLASS:
            // increment the class counter
            $class_counter++;
            // reset the method counter
            $method_counter = 0;
            // get the class name and store it in the result array
            $result[$class_counter]['class_name'] = $tokens[$i + 2][1];
            // get the class definition line and store it in the result array
            $result[$class_counter]['class_line'] = $line;
            // get class documentation
            $result[$class_counter]['class_documentation'] = self::get_class_or_method_comment($tokens, $i);
            // get class modifiers
            
            // get class extend
            
            // get class interface
            
            break;
          
          // if it is a method or function          
          case T_FUNCTION:
            // increment the method counter
            $method_counter++;
            // get the method name and store it in the result array
            $result[$class_counter]['methods'][$method_counter]['method_name'] = $tokens[$i + 2][1];
            // get the method definition line and store it in the result array
            $result[$class_counter]['methods'][$method_counter]['method_line'] = $line;
            // get method documentation
            $result[$class_counter]['methods'][$method_counter]['method_documentation'] = self::get_class_or_method_comment($tokens, $i);
            // get method paramters
            
            break;
          // if it is a comment of any type
          case T_COMMENT: 
          case T_ML_COMMENT:
          //case T_DOC_COMMENT:
            // store the comment in the methods documentation field
            $result[$class_counter]['methods'][$method_counter]['inline_documentation'][] = $text;
            break;
          // if it is something else 
          default:
            // ignore it
            break;
        }
      }
    }
   
    // TODO: get rid of this hack
    // if T_CLASS tokens were found, unset the first comment
    if($class_counter >= 0) {
      unset($result[-1]);
    }
   
    
    // return the parsed documentation
    return $result;
  }
  
  public static function extract_comments_from_file($filename) {
    return self::extract_comments(self::tokenize_file($filename));
  }
  
  public static function get_class_or_method_comment($tokens, $position) {
    // Initialize array of valid comment tokens
    $comments = array(T_COMMENT, T_ML_COMMENT, T_DOC_COMMENT);
    
    // Initialize array of ignored tokens
    $continue = array(T_ABSTRACT, T_FINAL, T_STATIC, T_CLASS, T_FUNCTION, T_WHITESPACE, T_PUBLIC, T_PRIVATE);
    
    // Loop backwards over tokens
    while($position >= 0) {
      // If the token is in the ignore array
      if(in_array($tokens[$position][0], $continue)) {
        // decrement to next position
        $position--;
        // continue loop
        continue;
      }
      
      // If the token is in the valid comments array, return the comment
      if(in_array($tokens[$position][0], $comments)) {
        return $tokens[$position][1];
      }
      
      // The actual token is neither in the ignored tokens nor in the valid
      // comments tokens array. Return false, as this class or method has no
      // documentation
      return false;
    }
    return false;
  }
}