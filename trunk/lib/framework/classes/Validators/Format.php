<?php
namespace Validators;
//require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class Format extends Base {
  private $model;
  private $field;
  private $pattern;
  private $message;
  
  public function __construct($model, $field, $pattern, $message) {
    $this->model   = $model;
    $this->field   = $field;
    $this->pattern = $pattern;  
    $this->message = $message;
  }
  
  public function validate() {
    $field = $this->field;
    if(preg_match($this->pattern, $this->model->$field)) {
      $this->model->remove_error_message_for($this->field);
      return true;
    }
    $this->model->set_error_message_for($this->field, sprintf($this->message, $this->field));
    return false;
  }
}    
