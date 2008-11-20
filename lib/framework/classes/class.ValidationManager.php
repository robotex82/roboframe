<?php
class ValidationManager {
//  protected $output_manager = null;
  
  public static function get_validator_for($validator_type, $data, $options) {
    switch ($validator_type) {
    case 'presence_of':
      return new ValidatorOfPresence($data, $options);
    case 'length_of':
      return new ValidatorOfLength($data, $options);
    default:
      throw new Exception('No validator for type ['.$validator_type.'] defined');
    }
  }
}
?>