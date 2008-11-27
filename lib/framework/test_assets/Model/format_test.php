<?php
class FormatTest extends Model {
  public function init() {
    $this->validates_format_of('birthdate', '/([0-9]{4})-([0-9]{2})-([0-9]{2})/');
  }
}