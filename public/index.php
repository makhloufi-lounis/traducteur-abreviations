<?php
chdir(dirname(__DIR__));
date_default_timezone_set('Europe/Paris');
defined ( 'APPLICATION_ENV' ) || define ( 'APPLICATION_ENV', (getenv ( 'APPLICATION_ENV' ) ? getenv ( 'APPLICATION_ENV' ) : 'development') );
// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}
// Setup autoloading
require 'init_autoloader.php';
// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
