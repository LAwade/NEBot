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
define('CONF_DB_DRIVER', 'pgsql');
define('CONF_DB_HOST', '159.203.176.221');
define('CONF_DB_PORT', '5432');
define('CONF_DB_BASE', 'lendariosbot');
define('CONF_DB_USER', 'lendarios');
define('CONF_DB_PASSWD', 'Hunt3r@195');
define('CONF_DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
]);

