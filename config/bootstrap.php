<?php
define('APP_BASE',         realpath(dirname(__FILE__).'/..'));
define('DATA_BASE',        realpath(dirname(__FILE__).'/../../roboframe_data'));
define('LIBRARY_PATH',     APP_BASE.'/lib');
define('FRAMEWORK_PATH',   APP_BASE.'/lib/framework');

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

require_once(FRAMEWORK_PATH.'/classes/Roboframe/class.Base.php');

Roboframe\Base::check_requirements();

Roboframe\Base::enable_modules('Inflector Registry Logger PluginManager Flash Generator Model Mailer Migration Migrator Router Route TaskGroup');

Roboframe\Base::set_environment(getenv('ROBOFRAME_ENV'));
Roboframe\Base::set_http_proxy(getenv('HTTP_PROXY'));
Roboframe\Base::set_logger(new Logger\Text());

require_once(APP_BASE.'/config/environment.php');

//Roboframe::enable_database();
Roboframe\Base::enable_sessions();

PluginManager\Base::initialize_all();

// @TODO: Find better way to autoload models
function __autoload($class_name) {
  $filename = strtolower(preg_replace('/([^\s])([A-Z])/', '\1_\2', $class_name)).'.php';
  $file = MODEL_ROOT.'/' . $filename;
  if(!file_exists($file)) { return false; }
  require_once($file);
}
?>