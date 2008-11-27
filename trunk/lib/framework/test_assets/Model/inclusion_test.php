<?php
class InclusionTest extends Model {
  public function init() {
    $this->validates_inclusion_of('name', 'bob, tom, fred');
  }
}