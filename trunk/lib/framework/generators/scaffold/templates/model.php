<|?php
use \ActiveRecord\Model;
class <?= $model_class ?> extends Model {
  public static function fields() {
    $columns = array();
    $t = new \ActiveRecord\Table(get_called_class());
    foreach($t->columns as $c) {
      $columns[] = $c->name;
    }
    return $columns;
  }
}