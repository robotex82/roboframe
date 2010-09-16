<|?php
namespace TaskGroup;
use Exception;
class <?= $group_name ?>Tasks extends Base {
  protected static $tasks = array(
<? $i = 0; ?>
<? foreach($tasks as $t) : ?>
  <? if($i > 0) : ?>,<? endif; ?>
  '<?= $t ?>' => ''
<? $i++; endforeach; ?>
  );
  
<? foreach($tasks as $t) : ?>
  public function <?= $t ?>($options) {
    return true;
  }
<? endforeach; ?>
}