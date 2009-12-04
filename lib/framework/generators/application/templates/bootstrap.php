<|?php
define('APP_BASE',         realpath(dirname(__FILE__).'/..'));
// define('DATA_BASE',        realpath(dirname(__FILE__).'/../data'));
define('LIBRARY_PATH',     '<?= $library_path ?>');
// TODO: Find a way to get the fw-path dynamically 
define('FRAMEWORK_PATH',   LIBRARY_PATH.'/framework');

define('APPLICATION_ROOT', APP_BASE.'/application');
define('MODEL_ROOT',       APPLICATION_ROOT.'/models');
define('VIEW_ROOT',        APPLICATION_ROOT.'/views');
define('LAYOUT_ROOT',      APPLICATION_ROOT.'/layouts');
define('PAGE_ROOT',        APPLICATION_ROOT.'/pages');
define('MIGRATION_ROOT',   APPLICATION_ROOT.'/migrations');

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPLICATION_ROOT);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PAGE_ROOT);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . LIBRARY_PATH);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . FRAMEWORK_PATH);

require_once(FRAMEWORK_PATH.'/classes/class.Roboframe.php');
Roboframe::check_requirements();

$modules = 'Inflector Registry Logger PluginManager Flash Generator Model Mailer Migration Migrator Router Route TaskGroup';
foreach(explode(' ', $modules) as $module) {
  Roboframe::enable_module($module);
}

require_once(APP_BASE.'/config/environment.php');


Roboframe::enable_database();
Roboframe::enable_sessions();

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