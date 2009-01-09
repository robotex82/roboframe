<?php
class TestMailer extends Mailer {
  public function user_notification() {
    //$this->sender('robotex82@arcor.de');
    $this->recipient('roberto.vasquez-angel@hp.com');
    $this->subject('my subject');
    $this->body('message body');
  }
}
?>