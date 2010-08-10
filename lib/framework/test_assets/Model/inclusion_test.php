<?php
namespace Model;
class InclusionTest extends Base {
  public function init() {
    $this->validates_inclusion_of('name', 'bob, tom, fred');
  }
}