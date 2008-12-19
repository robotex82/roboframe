<?php
class PresenceTest extends Model {
  public function init() {
    $this->validates_presence_of('name, password', '%s should be set');
  }
}