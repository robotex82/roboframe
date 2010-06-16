<|? foreach(<?= $model_class ?>::fields() as $field) : ?>
  <|?= form_field_for($<?= $us_model_class ?>, $field); ?>
<|? endforeach; ?>

<p><input type="submit" value="submit"></input></p>