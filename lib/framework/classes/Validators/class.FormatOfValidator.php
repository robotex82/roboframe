<?php
require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class FormatOfValidator extends Validator {
  private $model;
  private $field;
  private $pattern;
  
  public function __construct($model, $field, $pattern) {
    $this->model   = $model;
    $this->field   = $field;
    $this->pattern = $pattern;  
  }
  
  public function validate() {
    $field = $this->field;
    if(preg_match($this->pattern, $this->model->$field)) {
      return true;
    }
    return false;
  }
}    
