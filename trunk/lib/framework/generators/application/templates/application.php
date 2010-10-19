<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>default</title>
<?= stylesheet_link_tag('default'); ?>
<meta name="keywords" content="" />
<meta name="description" content="" />
</head>
<body>
  <div id="content">
    <div class="flash">
      <? if($flash['notice']) : ?>
        <p style="color: green"><?= $flash['notice']; ?></p>
      <? endif; ?>
      <? if($flash['message']) : ?>
        <p style="color: green"><?= $flash['message']; ?></p>
      <? endif; ?>
      <? if($flash['warning']) : ?>
        <p style="color: red"><?= $flash['warning']; ?></p>
      <? endif; ?>
      <? if($flash['error']) : ?>
        <p style="color: red"><?= $flash['error']; ?></p>
      <? endif; ?>
    </div>
    <? include($content); ?>
  </div>
</body>
</html>


