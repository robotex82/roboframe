<?
define('APP_BASE', realpath(dirname(__FILE__).'/..'));
define('DATA_BASE', realpath(dirname(__FILE__).'/../../roboframe_data'));
define('LIBRARY_PATH', APP_BASE.'/lib');
define('FRAMEWORK_PATH', APP_BASE.'/lib/framework');

define('APPLICATION_ROOT', APP_BASE.'/application');
define('MODEL_ROOT', APPLICATION_ROOT.'/models');
define('PAGE_ROOT', APPLICATION_ROOT.'/pages');
define('MIGRATION_ROOT', APPLICATION_ROOT.'/migrations');

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPLICATION_ROOT);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PAGE_ROOT);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . LIBRARY_PATH);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . FRAMEWORK_PATH);

require_once(FRAMEWORK_PATH.'/classes/class.Roboframe.php');
Roboframe::check_requirements();

$modules = 'Logger Inflector Registry PluginManager Flash Model Mailer Migration Migrator Router Route';
foreach(explode(' ', $modules) as $module) {
  Roboframe::enable_module($module);
}
/*
require_once(FRAMEWORK_PATH.'/classes/class.Logger.php');
require_once(FRAMEWORK_PATH.'/classes/class.Inflector.php');
require_once(FRAMEWORK_PATH.'/classes/class.Registry.php');
require_once(FRAMEWORK_PATH.'/classes/class.PluginManager.php');
require_once(FRAMEWORK_PATH.'/classes/class.Flash.php');
require_once(FRAMEWORK_PATH.'/classes/class.Model.php');
require_once(FRAMEWORK_PATH.'/classes/class.Mailer.php');
require_once(FRAMEWORK_PATH.'/classes/class.Migration.php');
require_once(FRAMEWORK_PATH.'/classes/class.Router.php');
require_once(FRAMEWORK_PATH.'/classes/class.Route.php');
*/
require_once(APP_BASE.'/config/environment.php');
require_once(APP_BASE.'/config/application.php');
//require_once(APP_BASE.'/config/views.php');

Roboframe::enable_database();

PluginManager::initialize_all();

// @TODO: Find better way to autoload models
function __autoload($class_name) {
    //$filename = strtolower($class_name) . '.class.php';
    $filename = strtolower(preg_replace('/([^\s])([A-Z])/', '\1_\2', $class_name)).'.php';
    $file = MODEL_ROOT.'/' . $filename;
    if (file_exists($file) == false)
    {
        return false;
    }
  include ($file);
}
?>