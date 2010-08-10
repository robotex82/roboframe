<?php
namespace TaskGroup;
class GreeterTasks extends Base {
  protected static $tasks = array('hello' =>''
                                , 'how_are_you' => 'hello');

  public function hello($options) {
    echo "Hello {$options['name']}!";
    return true;
  }
  
  public function how_are_you() {
    echo "How are you?";
    return true;
  }
}