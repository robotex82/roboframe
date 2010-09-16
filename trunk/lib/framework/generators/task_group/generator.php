<?php
namespace Generator;
use Exception;
class TaskGroup extends Base {
  public $option_mappings = array(0 => 'group_name', 1 => 'tasks');
  
  public function commands() {
    if(!is_string($this->tasks)) {
      throw new Exception("Missing parameter form task_group generator! Can't generate task group without tasks!");
    }
    
    $this->template_root       = dirname(__FILE__).'/templates';
    $this->library_path        = realpath(dirname(__FILE__).'/../../../');
    $this->app_root            = getcwd();
    $this->task_path           = $this->app_root.'/lib/tasks';
    $this->task_filename       = \Inflector\Base::underscore($this->group_name).'_tasks.php';
    $this->tasks               = explode(',', $this->tasks);

    $this->template($this->template_root.'/task_group.php',
                    $this->task_path.'/'.$this->task_filename,
                    array('group_name' => $this->group_name,
                          'tasks' => $this->tasks)
                    );
  }
}
?>