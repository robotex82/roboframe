<?php
require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class InclusionOfValidator extends Validator {
  private $model;
  private $field;
  private $possible_values = array();
  
  public function __construct($model, $field, $possible_values) {
    $this->model  = $model;
    $this->field  = $field;
    $this->possible_values = $possible_values;  
  }
  
  public function validate() {
    $field = $this->field;
    if(!in_array($this->model->$field, $this->possible_values)) {
      return false;
    } 
    return true;  
  }
}
