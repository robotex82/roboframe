<?php
class LengthTest extends Model {
  public function init() {
    $this->validates_length_of('name is 5', 'password is 8');
  }
}