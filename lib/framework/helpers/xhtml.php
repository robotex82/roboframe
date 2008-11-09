<?php
function stylesheet_link_tag($name) {
  echo('<link href="stylesheets/'.$name.'.css" media="screen" rel="stylesheet" type="text/css" />');
}
?>