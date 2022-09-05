<?php

/*
|--------------------------------------------------------------------------
| PROJECT
|--------------------------------------------------------------------------
|
*/
define('CONF_PROJECT_BOTNAME', 'NEBOT');
define('CONF_PROJECT_VERSION', '1.0.0');

/*
|--------------------------------------------------------------------------
| LOGGER
|--------------------------------------------------------------------------
|
*/
define('CONF_LOGGER_STATUS', true);
define('CONF_LOGGER_PATH', __DIR__ . '/../storage/logs/');

/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
|
*/
define('CONF_DATE_BR', 'd/m/Y');
define('CONF_DATE_HOUR_BR', 'd/m/Y H:i:s');
define('CONF_DATE_APP', 'Y-m-d');
define('CONF_DATE_HOUR_APP', 'Y-m-d H:i:s');

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
|
*/
define('CONF_DB_DRIVER', getenv('CONF_DB_DRIVER'));
define('CONF_DB_HOST', getenv('CONF_DB_HOST'));
define('CONF_DB_PORT', getenv('CONF_DB_PORT'));
define('CONF_DB_BASE', getenv('CONF_DB_BASE'));
define('CONF_DB_USER', getenv('CONF_DB_USER'));
define('CONF_DB_PASSWD', getenv('CONF_DB_PASSWD'));
define('CONF_DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
]);

/*
|--------------------------------------------------------------------------
| MONGODB
|--------------------------------------------------------------------------
|
*/
define('CONF_MONGODB_USER', 'admin');
define('CONF_MONGODB_PASSWD', 'Hunt3r195');
define('CONF_MONGODB_HOST', '152.67.45.241:27017');
define('CONF_MONGODB_COLLECTIONS', 'tibiabot');
define('CONF_MONGODB_SOURCE', 'admin');
]);
