<?php
define('APP_BASE',         realpath(dirname(__FILE__).'/..'));
define('LIBRARY_PATH',     dirname(__FILE__).'/../lib');
define('FRAMEWORK_PATH',   LIBRARY_PATH.'/framework');

require_once(FRAMEWORK_PATH.'/classes/Roboframe/Base.php');

Roboframe\Base::check_requirements();
Roboframe\Base::set_environment(getenv('ROBOFRAME_ENV'));
Roboframe\Base::set_http_proxy(getenv('HTTP_PROXY'));

Roboframe\Base::enable_module('Logger\Text');
Roboframe\Base::enable_module('PluginManager\Base');
Roboframe\Base::enable_module('Generator\Manager\Base');
Roboframe\Base::enable_module('Generator\Base');
Roboframe\Base::enable_module('Inflector\Base');

require_once(APP_BASE.'/config/environment.php');

Roboframe\Base::enable_sessions();
PluginManager\Base::initialize_all();