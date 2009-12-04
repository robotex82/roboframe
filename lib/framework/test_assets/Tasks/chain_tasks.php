<?php
class ChainTasks extends TaskGroup {
  protected static $tasks = array('buy_car' =>'withdraw_money'
                                , 'withdraw_money' => 'get_salary'
                                , 'get_salary' => 'go_to_work'
                                , 'go_to_work' => 'stand_up'
                                , 'stand_up' => '');

  public function stand_up($options) {
    echo "{$options['name']} stands up!";
    return true;
  }
  
  public function go_to_work($options) {
    echo "{$options['name']} works for {$options['hours']} hours!";
    return true;
  }
  
  public function get_salary($options) {
    echo "{$options['name']} gets salary ({$options['amount']})!";
    return true;
  }
  
  public function withdraw_money($options) {
    echo "{$options['name']} withdraws {$options['amount']} euros!";
    return true;
  }
  
  public function buy_car($options) {
    echo "{$options['name']} buys a fancy new {$options['model']} car!";
    return true;
  }
}