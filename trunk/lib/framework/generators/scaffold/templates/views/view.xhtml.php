<h1>View <?= $model_class ?> <|?= $<?= $us_model_class?>->id ?></h1>
<|?= link_to("List <?= $model_class ?>", array("controller" => "<?= $us_controller_class ?>", "action" => "enlist")) ?>

<|? foreach(<?= $model_class ?>::fields() as $field) : ?>
  <p><|?= $field ?>: <|?= $<?= $us_model_class?>->$field ?></p>
<|? endforeach; ?>
<|?= link_to("Edit",   array("controller" => "<?= $us_controller_class ?>", "action" => "edit",   "id" => $<?= $us_model_class?>->id)) ?> 
 | <|?= link_to("Delete", array("controller" => "<?= $us_controller_class ?>", "action" => "delete", "id" => $<?= $us_model_class?>->id)) ?>