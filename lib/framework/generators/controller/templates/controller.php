<|?php
class <?= $class_name ?>Controller extends ApplicationController {
<? foreach(explode(',', $methods) as $method) : ?>
  public function <?= $method ?>() {}
<? endforeach; ?>
}