<?php
class ApplicationGenerator extends Generator {
  public $option_mappings = array(0 => 'app_name');
  
  public function commands() {
    $this->app_root      = getcwd()."/{$this->app_name}";
    $this->template_root = dirname(__FILE__).'/templates';
    $this->library_path  = realpath(dirname(__FILE__).'/../../../');
    
    $this->directory($this->app_root);
    $this->directory($this->app_root.'/application');
    $this->directory($this->app_root.'/application/models');
    $this->directory($this->app_root.'/application/views');
    $this->directory($this->app_root.'/application/controllers');
    $this->directory($this->app_root.'/application/helpers');
    $this->directory($this->app_root.'/application/layouts');
    $this->directory($this->app_root.'/config');
    $this->directory($this->app_root.'/config/environments');
    $this->directory($this->app_root.'/lib');
    $this->directory($this->app_root.'/lib/tasks');
    $this->directory($this->app_root.'/logs');
    $this->directory($this->app_root.'/db');
    $this->directory($this->app_root.'/db/migrate');
    $this->directory($this->app_root.'/plugins');
    $this->directory($this->app_root.'/public');
    $this->directory($this->app_root.'/public/stylesheets');
    $this->directory($this->app_root.'/scripts');
    $this->directory($this->app_root.'/tests');
    $this->directory($this->app_root.'/tests/fixtures');
    $this->directory($this->app_root.'/tests/models');

    $source = $this->template_root.'/application_controller.php'; 
    $target = $this->app_root.'/application/controllers/application_controller.php'; 
    $this->file($source, $target);
    
    $this->file($this->template_root.'/application_helpers.php',
                $this->app_root.     '/application/helpers/application_helpers.php');

    $this->file($this->template_root.'/application.php',
                $this->app_root.     '/application/layouts/application.php');
                
    $this->template($this->template_root.'/bootstrap.php',
                    $this->app_root.     '/config/bootstrap.php',
                    array('library_path' => $this->library_path));
                
    $this->template($this->template_root.'/database.ini',
                    $this->app_root.     '/config/database.ini',
                    array('app_name' => $this->app_name));

    $this->file($this->template_root.'/environment.php',
                $this->app_root.     '/config/environment.php');
                
    $this->file($this->template_root.'/routes.ini',
                $this->app_root.     '/config/routes.ini');
                
    $this->file($this->template_root.'/development.php',
                $this->app_root.     '/config/environments/development.php');
                
    $this->file($this->template_root.'/test.php',
                $this->app_root.     '/config/environments/test.php');
                
    $this->file($this->template_root.'/production.php',
                $this->app_root.     '/config/environments/production.php');
                
    $this->template($this->template_root.'/.htaccess',
                    $this->app_root.     '/public/.htaccess',
                    array('app_name' => $this->app_name));
                    
    $this->file($this->template_root.'/welcome.php',
                $this->app_root.     '/public/welcome.php');

    $this->file($this->template_root.'/dispatch.php',
                $this->app_root.     '/public/dispatch.php');
                
    $this->file($this->template_root.'/default.css',
                $this->app_root.     '/public/stylesheets/default.css');

                
    $this->file($this->template_root.'/generate.php',
                $this->app_root.     '/scripts/generate.php');
                
    $this->file($this->template_root.'/task.php',
                $this->app_root.     '/scripts/task.php');

    $this->file($this->template_root.'/test.php',
                $this->app_root.     '/scripts/test.php');  
  }
}
?>