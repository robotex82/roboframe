<h1>Edit <?= $model_class ?> <|?= $<?= $us_model_class?>->id ?></h1>
<|?= link_to("List <?= $model_class ?>", array("controller" => "<?= $us_controller_class ?>", "action" => "enlist")) ?> | <|?= link_to("Back", back_url()); ?>
<form action="<|?= Router\Base::base_url(); ?>/<?= $us_controller_class ?>/update" method="post">
  <|? $this->render_partial("form.xhtml", array('<?= $us_model_class?>' => $<?= $us_model_class?>)); ?>
</form>