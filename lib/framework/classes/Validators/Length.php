<?php
namespace Validators;
//require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class Length extends Base {
  private $model;
  private $field;
  private $length;
  
  public function __construct($model, $field, $length) {
    $this->model  = $model;
    $this->field  = $field;
    $this->length = $length;  
  }
  
  public function validate() {
    $field = $this->field;
    if(strlen($this->model->$field) < $this->length) {
      return false;
    } 
    return true;  
  }
}
