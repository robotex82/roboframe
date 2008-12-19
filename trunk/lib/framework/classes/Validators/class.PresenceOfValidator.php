<?php
require_once(FRAMEWORK_PATH.'/classes/class.Validator.php');
class PresenceOfValidator extends Validator {
  private $model;
  private $fields = array();
  
  public function __construct($model, array $fields, $message) {
    $this->model  = $model;
    $this->fields = $fields;
    if(empty($message)) {
      $this->message = '%s should not be empty';
    } else {
      $this->message = $message;
    }
  }
  
  public function validate() {
    $return = true;
    foreach($this->fields as $field) {
      if($this->model->$field == null) {
        $this->model->set_error_message_for($field, sprintf($this->message, $field));
        $return = false;
      } else {
        $this->model->remove_error_message_for($field);
      }
    }
    return $return;  
  }
}