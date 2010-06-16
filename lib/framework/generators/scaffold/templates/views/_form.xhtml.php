<|? foreach(<?= $model_class ?>::fields() as $field) : ?>
  <|?= form_field_for(<?= $model_class ?>, $field, $<?= $us_model_class ?>); ?>
<|? endforeach; ?>

<p><input type="submit" value="submit"></input></p>