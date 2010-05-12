<?php
namespace CodeDocumentor;

class TextOutput {
  public static function generate_documentation($filename, $parsed_file) {
    $result = array();
    
    $result[] = "Documentation for file: {$filename}";
    $result[] = "================================================================================";
    foreach($parsed_file as $cd) {
      $result[] = "Class: {$cd['class_name']} (Line: {$cd['class_line']})";
      $result[] = "Short description: {$cd['class_documentation']}";
      $result[] = "";
      $result[] = "Methods:";
      $result[] = "--------------------------------------------------------------------------------";
      foreach($cd['methods'] as $md) {
        $result[] = "Method: {$md['method_name']} (Line: {$md['method_line']})";
        $result[] = "Short description: {$md['method_documentation']}";
        $result[] = "";      
        $result[] = "Implementation details:";
        foreach($md['inline_documentation'] as $id) {
          $result[] = " * ".trim($id);
        }
        $result[] = ""; 
        $result[] = "--------------------------------------------------------------------------------";
      }
    }
    return $result;
  }
}