<h1><?= $model_class ?> (<|?= $<?= $us_model_class?>::count(); ?>)</h1>
<|?= link_to("Add <?= $model_class?>", array("controller" => "<?= $us_controller_class ?>", "action" => "add")) ?>
<table>
  <tr>
<|? foreach(<?= $model_class ?>::fields() as $field) : ?>
    <th><|?= \Inflector\Base::humanize($field) ?></th>    
<|? endforeach; ?>
    <th colspan="3">Actions</th>
  </tr>  
<|? foreach($<?= $pl_model_class ?> as $<?= $us_model_class?>) : ?>
  <tr>
  <|? foreach(<?= $model_class?>::fields() as $field) : ?>
    <td><|?= $<?= $us_model_class?>->$field ?></td>
  <|? endforeach; ?>
    <td><|?= link_to("View",   array("controller" => "<?= $us_controller_class ?>", "action" => "view",   "id" => $<?= $us_model_class?>->id)) ?></td>
    <td><|?= link_to("Edit",   array("controller" => "<?= $us_controller_class ?>", "action" => "edit",   "id" => $<?= $us_model_class?>->id)) ?></td>
    <td><|?= link_to("Delete", array("controller" => "<?= $us_controller_class ?>", "action" => "delete", "id" => $<?= $us_model_class?>->id)) ?></td>
  </tr>  
<|? endforeach; ?>
</table>