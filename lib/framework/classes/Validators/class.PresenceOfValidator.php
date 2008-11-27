<?php
require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class PresenceOfValidator extends Validator {
  private $model;
  private $fields = array();
  
  public function __construct($model, array $fields) {
    $this->model  = $model;
    $this->fields = $fields;
  
  }
  
  public function validate() {
    foreach($this->fields as $field) {
      if($this->model->$field == null) {
        return false;
      } 
    }
    return true;  
  }
}