<h1>Add <?= $model_class ?></h1>
<|?= link_to("List <?= $model_class ?>", array("controller" => "<?= $us_controller_class ?>", "action" => "enlist")) ?>
<form action="<|?= Router\Base::base_url(); ?>/<?= $us_controller_class ?>/create" method="post">
  <|? $this->render_partial("form.xhtml"); ?>
</form>