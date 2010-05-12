<?php
namespace Generator;
class TaskGroup extends Base {
  public $option_mappings = array(0 => 'group_name');
  
  public function commands() {
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->task_path           = $this->app_root.'/lib/tasks';
    $this->task_filename       = \Inflector\Base::underscore($this->group_name).'_tasks.php';

    $this->template($this->template_root.'/task_group.php',
                    $this->task_path.'/'.$this->task_filename,
                    array('group_name' => $this->group_name));
  }
}
?>