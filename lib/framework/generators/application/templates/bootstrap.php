<|?php
define('APP_BASE',         realpath(dirname(__FILE__).'/..'));
define('DATA_BASE',        realpath(dirname(__FILE__).'/../../roboframe_data'));
define('LIBRARY_PATH',     '<?= $library_path ?>');
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

require_once(FRAMEWORK_PATH.'/classes/Roboframe/Base.php');

Roboframe\Base::check_requirements();

// Core Modules
Roboframe\Base::enable_module('Inflector\Base');
Roboframe\Base::enable_module('PluginManager\Base');
Roboframe\Base::enable_module('Registry\Base');

// Logging Modules
Roboframe\Base::enable_module('Logger\Base');
Roboframe\Base::enable_module('Logger\Text');

// Tool Modules
Roboframe\Base::enable_module('Generator\Manager\Base');
Roboframe\Base::enable_module('Generator\Base');
Roboframe\Base::enable_module('TaskGroup\Manager\Base');
Roboframe\Base::enable_module('TaskGroup\Base');

// Mailer Modules
Roboframe\Base::enable_module('Mailer\Base');

// Web Modules
Roboframe\Base::enable_module('Request\Base');
Roboframe\Base::enable_module('Router\Base');
Roboframe\Base::enable_module('Route\Base');
Roboframe\Base::enable_module('View\Base');
Roboframe\Base::enable_module('Flash\Base');
Roboframe\Base::enable_module('Output\Manager\Base');
Roboframe\Base::enable_module('Controller\Base');
Roboframe\Base::enable_module('Controller\Front');
Roboframe\Base::enable_module('Controller\Action');

// Database Modules
Roboframe\Base::enable_module('Migration\Base');
Roboframe\Base::enable_module('Migrator\Base');

// Model Modules
/*
Roboframe\Base::enable_module('Model\Base');
Roboframe\Base::enable_module('Validators\PresenceOfValidator');
Roboframe\Base::enable_module('Validators\LengthOfValidator');
Roboframe\Base::enable_module('Validators\InclusionOfValidator');
Roboframe\Base::enable_module('Validators\FormatOfValidator');
*/

Roboframe\Base::set_environment(getenv('ROBOFRAME_ENV'));
Roboframe\Base::set_http_proxy(getenv('HTTP_PROXY'));


require_once(APP_BASE.'/config/environment.php');

//Roboframe::enable_database();
Roboframe\Base::enable_sessions();

PluginManager\Base::initialize_all();

// @TODO: Find better way to autoload models
/*
function __autoload($class_name) {
  $filename = strtolower(preg_replace('/([^\s])([A-Z])/', '\1_\2', $class_name)).'.php';
  $file = MODEL_ROOT.'/' . $filename;
  if(!file_exists($file)) { return false; }
  require_once($file);
}
*/
?>