<?php
namespace Mailer;
use Exception;
class TestMailer extends Base {
  public function user_notification() {
    //$this->sender('robotex82@arcor.de');
    $this->recipients('roberto.vasquez-angel@hp.com');
    $this->subject('my subject');
  }
  
  public function test_mail() {
    
  }
  
  public function test_dynamic_mail() {
    $this->username = 'bob';
  }
}
?>