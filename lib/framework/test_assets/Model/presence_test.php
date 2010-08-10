<?php
namespace Model;
class PresenceTest extends Base {
  public function init() {
    $this->validates_presence_of('name, password', '%s should be set');
  }
}