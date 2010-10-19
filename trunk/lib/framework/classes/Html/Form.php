<?php
namespace Html;
use Exception;
use Inflector\Base as Inflector;

class Form {
  private $_object;
  
  public function set_object($object) { $this->_object = $object; }
  public function object() { return $this->_object; }
  
  private $_object_class;
  
  public function set_object_class($object_class) { $this->_object_class = $object_class; }
  public function object_class() { return $this->_object_class; }
  
  
  
  public function __construct($object) {
    $this->set_object_class(get_class($object));
    $this->set_object($object);
  }
  
  public function error_message_for($field) {
    $error = @$this->object()->errors->$field;
    return $error[0];
  }  
  
  public function error_messages() {
    return @$this->object()->errors;
  }
  
  public function checkbox($name) {
    $options = array(
      'id'    => Inflector::underscore($this->object_class()).'_'.$name
     ,'name'  => Inflector::underscore($this->object_class()).'['.$name.']'
    );
    
    $hidden_options = array(
      'type'  => 'hidden'
     ,'value' => '0'
    );
    $output = self::tag_builder('input', array_merge($options, $hidden_options));
    
    $checkbox_options = array(
      'type'  => 'checkbox'
     ,'value' => '1' 
    );

    if((@$this->object()->$name == true)) {
      $checkbox_options['checked'] = 'checked';
    }
    
    $output.= self::tag_builder('input', array_merge($options, $checkbox_options));
    return $output;
  }
  
  public function label($name, array $options = array()) {
    $options = array(
      'id' =>  $name
     ,'for' => Inflector::underscore($this->object_class()).'_'.$name
     ,'tag_content' => Inflector::humanize($name)
     //,'value' => @$this->object()->$name
    );
    $output = self::tag_builder('label', array_merge($options));
    return $output;
  }

  
  public function select($name, array $collection = array(), array $options = array()) {
    $option_tags = array();
    
    foreach($collection as $o) {
      if(@$this->object()->$name == $o[0]) {
        $option_tags[]= self::tag_builder('option', array('value' => $o[0], 'tag_content' => $o[1], 'selected' => 'selected'));
      } else {
        $option_tags[]= self::tag_builder('option', array('value' => $o[0], 'tag_content' => $o[1]));
      }
      
    }
    
    $select_options = array('name'        => Inflector::underscore($this->object_class()).'['.$name.']'
                           , 'id'         => Inflector::underscore($this->object_class()).'_'.$name
                           ,'tag_content' => join("\r\n", $option_tags));
                           
    $select = self::tag_builder('select', $select_options);
    return $select;
  }
  
  public function text_field($name) {
    $options = array(
      'id'    => Inflector::underscore($this->object_class()).'_'.$name
     ,'name'  => Inflector::underscore($this->object_class()).'['.$name.']'
     ,'value' => @$this->object()->$name
     ,'type'  => 'text'
    );
    $output = self::tag_builder('input', $options);
    return $output;
  }
  
  public function password_field($name) {
    $options = array(
      'id'    => Inflector::underscore($this->object_class()).'_'.$name
     ,'name'  => Inflector::underscore($this->object_class()).'['.$name.']'
     ,'type'  => 'password'
    );
    $output = self::tag_builder('input', $options);
    return $output;
  }
  
  public function submit() {
    $options = array(
      'value' => 'Submit'
     ,'type' => 'submit'
    );
    $output = self::tag_builder('input', $options);
    return $output;
  }
  
  public function textarea($name) {
    $options = array(
      'id'    => Inflector::underscore($this->object_class()).'_'.$name
     ,'name'  => Inflector::underscore($this->object_class()).'['.$name.']'
     ,'tag_content' => @$this->object()->$name
    );
    $output = self::tag_builder('textarea', $options);
    return $output;
  }
  
  public static function tag_builder($tag, array $options = array()) {
    $output = '<'.$tag;
    if(array_key_exists('action', $options)) {
      $output.= ' action="'.$options['action'].'"';
    }
    
    if(array_key_exists('id', $options)) {
      $output.= ' id="'.$options['id'].'"';
    }
    
    if(array_key_exists('value', $options)) {
      $output.= ' value="'.$options['value'].'"';
    }
    
    if(array_key_exists('for', $options)) {
      $output.= ' for="'.$options['for'].'"';
    }
    
    if(array_key_exists('type', $options)) {
      $output.= ' type="'.$options['type'].'"';
    }
    
      if(array_key_exists('method', $options)) {
      $output.= ' method="'.$options['method'].'"';
    }
    
    if(array_key_exists('name', $options)) {
      $output.= ' name="'.$options['name'].'"';
    }
    
    if(array_key_exists('checked', $options)) {
      $output.= ' checked="checked"';
    }
    
    if(array_key_exists('selected', $options)) {
      $output.= ' selected="selected"';
    }
    
    if(!array_key_exists('tag_content', $options)) {
      $output.= ' />';
      return $output;    
    }

    $output.= '>'.$options['tag_content'].'</'.$tag.'>';
    return $output;
  }
}
