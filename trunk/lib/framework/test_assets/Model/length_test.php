<?php
namespace Model;
class LengthTest extends Base {
  public function init() {
    $this->validates_length_of('name is 5', 'password is 8');
  }
}