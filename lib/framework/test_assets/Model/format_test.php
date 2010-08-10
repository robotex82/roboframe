<?php
namespace Model;
class FormatTest extends Base {
  public function init() {
    $this->validates_format_of('birthdate', '/([0-9]{4})-([0-9]{2})-([0-9]{2})/', '%s should have the right format');
  }
}