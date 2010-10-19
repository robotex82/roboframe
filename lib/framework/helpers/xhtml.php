<?php
function stylesheet_link_tag($name) {
  return '<link href="'.Router\Base::base_url().'/stylesheets/'.$name.'.css" media="screen" rel="stylesheet" type="text/css" />';
}

function javascript_link_tag($name) {
  return '<script type="text/javascript" src="'.Router\Base::base_url().'/'.$name.'.js"></script>';
}

function back_url() {
  return @$_SERVER['HTTP_REFERER'];
}
/*
 * Params:
 * name
 * value
 * id
 */
/*
function text_field($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  return '<input type="text" name="'.$options['name'].'" value="'.$options['value'].'" id="'.$options['id'].'"></input>';
}
*/
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
/*
function submit($args) {
  $args_array = explode(', ', $args);
  foreach($args_array as $value) {
    $exploded_value = explode(' => ', $value);
    $options[$exploded_value[0]] = substr($exploded_value[1], 1, -1);
  }
  return '<input type="submit" value="'.$options['value'].'" class="'.$options['class'].'"></input>';
}
*/

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

function link_to($label, $options) {
  if(is_array($options)) {
    $output = "<a href=\"".Router\Base::base_url()."/{$options['controller']}";
    if(isset($options['action'])) {
    $output.= "/{$options['action']}";
    }
    
    if(isset($options['id'])) {
      $output.= "/{$options['id']}";
    }
    $output.= "\">{$label}</a>";
    echo $output;
  }
  if(is_string($options)) {
    echo "<a href=\"{$options}\">{$label}</a>";
  }
}

function mail_to_link($target, $text = false) {
  if(!$text) {
    echo '<a href="mailto:'.$target.'">'.$target.'</a>';
  } else {
    echo '<a href="mailto:'.$target.'">'.$text.'</a>';
  }
}


///////////////////////////////////////////////////////////////
function check_box_tag($name, $value = "1", $checked = false, array $options = array()) {
  $html_options = array_merge(array("type" => "checkbox", "name" => $name, "id" => sanitize_to_id($name), "value" => $value), $options); //.update(options.stringify_keys)
  if($checked) {
    $html_options['checked'] = 'checked';
  }
  return tag('input', $html_options);
}

function content_tag($name, $content_or_options_with_block = null, $options = null, $escape = true) {
  return content_tag_string($name, $content_or_options_with_block, $options, $escape);
}

function content_tag_string($name, $content, $options, $escape = true) {
  if($options) {
    $tag_options = tag_options($options, $escape);
  }
  if($escape) {
    $content = html_escape($content);
  }
  
  return "<{$name} {$tag_options}>{$content}</{$name}>"; //.html_safe;
}

function end_form_tag() {
  return '</form>';
}

function error_messages_for($object) {
  if(count($object->errors) == 0) return;
  $output = '<ul>';
  foreach($object->errors as $error) {
    $output.= '<li>'.$error.'</li>';
  }
  $output.= '</ul>';
  return $output;
}

function html_escape($s) {
    return str_replace(array('&', '"', '>', '<'), array('&amp;', '&quot;', '&gt;', '&lt;'), $s);
}

function hidden_field_tag($name, $value, array $options = array()) {
  $options['type'] = 'hidden';
  return text_field_tag($name, $value, $options);
}

function label_tag($name, $text = null, array $options = array()) {
  $text_or_name = ($text) ? $text : Inflector::humanize($name);
  return content_tag('label', $text_or_name, array_merge(array('for' => sanitize_to_id($name)), $options));
}

function sanitize_to_id($name) {
  $output = str_replace(']', '', $name);
  $output = preg_replace('/[^-a-zA-Z0-9:.]/', '', $output);
  return $output;
}

function start_form_tag(array $options = array()) {
  $html_options['method'] = 'post';
  $html_options['action'] = Router\Base::base_url().'/'.$options['controller'].'/'.$options['action'];
  return \Html\Form::tag_builder('form', $html_options);
}

function tag($name, $options = null, $open = false, $escape = true) {
  $output = "<{$name} ";
  if($options) {
    $output.= tag_options($options, $escape);
  }
  $output.= ($open) ? '>' : ' />';
  return $output;
  //return #{open ? ">" : " />"}".html_safe;
}

function tag_options($options, $escape = true) {
  $boolean_attributes = explode(' ', "disabled readonly multiple checked autobuffer autoplay controls loop selected hidden scoped async defer reversed ismap seemless muted required autofocus novalidate formnovalidate open");
  if(!empty($options)) {
    $attrs = array();
    foreach($options as $key => $value) {
      if(in_array($key, $boolean_attributes)) {
        if($value) {
          $attrs[] = "{$key}=\"{$key}\""; 
        }
      } elseif(!is_null($value)) {
        $final_value = (is_array($value)) ? join(' ', $value): $value;
        if($escape) $final_value = html_escape($final_value);
        $attrs[] = "{$key}=\"{$final_value}\""; 
      }
    }
    if(!empty($attrs)) {
      sort($attrs);
      return join(' ', $attrs); //html_safe
    }
  }
}


function text_field($object_class, $name, array $options = array()) {
  $options = array(
    'id'    => $object_class.'_'.$name
   ,'name'  => $object_class.'['.$name.']'
   //,'value' => @$this->object()->$name
   ,'type'  => 'text'
  );
  $output = \Html\Form::tag_builder('input', $options);
  return $output;
}

function text_field_tag($name, $value = null, array $options = array()) {
  $defaults = array( "type" => "text", "name" => $name, "id" => sanitize_to_id($name), "value" => $value );
  return tag('input', array_merge($defaults, $options));
  
}


function password_field($object_class, $name, array $options = array()) {
  $options = array(
    'id'    => $object_class.'_'.$name
   ,'name'  => $object_class.'['.$name.']'
   //,'value' => @$this->object()->$name
   ,'type'  => 'password'
  );
  $output = \Html\Form::tag_builder('input', $options);
  return $output;
}

function submit_tag($value) {
  $options = array(
    'id'    => 'submit'
   ,'name'  => 'submit'
   ,'value' => $value
   ,'type'  => 'submit'
  );
  $output = \Html\Form::tag_builder('input', $options);
  return $output;
}