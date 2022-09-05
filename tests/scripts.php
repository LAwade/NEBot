<?php 

include_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    print_r([getenv('CONF_MONGODB_USER'), getenv('CONF_MONGODB_PASSWD'), getenv('CONF_MONGODB_HOST'), getenv('CONF_MONGODB_COLLECTIONS'), getenv('CONF_MONGODB_SOURCE')]);

?>