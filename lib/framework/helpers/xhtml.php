<?php
function stylesheet_link_tag($name) {
  return '<link href="'.Router::base_url().'/stylesheets/'.$name.'.css" media="screen" rel="stylesheet" type="text/css" />';
}

function back_url() {
  return $_SERVER['HTTP_REFERER'];
}
/*
 * Params:
 * name
 * value
 * id
 */
function text_field($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  return '<input type="text" name="'.$options['name'].'" value="'.$options['value'].'" id="'.$options['id'].'"></input>';
}

/*
 * Params:
 * name
 * value
 * id
 * cols
 * rows
 */
function text_area($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  return '<textarea name="'.$options['name'].' id="'.$options['id'].'" cols="'.$options['cols'].'" rows="'.$options['rows'].'">'.$options['value'].'</textarea>';
}

/*
 * Params:
 * for
 * value
 */
function label($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
//  $options['value'] = substr($options['value'], 1, -1);

  
  return '<label for="'.$options['for'].'">'.$options['value'].'</label>';
}

/*
 * Params:
 * value
 * class
 */
function submit($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  return '<input type="submit" value="'.$options['value'].'" class="'.$options['class'].'"></input>';
}


function errors_message_for_field($args, $flash) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  ?><div class="error_message"><?= $flash->error_messages[$options['fieldname']] ?></div>
  <?
}

function errors_message_for($args, $flash) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  print_r($args);
  print_r($flash);
  ?><div class="error_message"><?= $flash->error_messages[$options['model']['property']] ?></div>
  <?
}

function human_size($bytes) {
//  echo $bytes;
  $unit = 0;
  $units = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB');
  while($bytes > 1024) {
    $bytes = $bytes / 1024;
    $unit++;
  }
  echo round($bytes, 2)." ".$units[$unit];
}

function checkbox($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  
//  print_r($options);
  ?><input type="checkbox" name="<?= $options['name'] ?>" id="<?= $options['id'] ?>" value="<?= $options['value'] ?>" /><?
}