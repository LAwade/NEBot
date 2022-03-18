<?php

namespace App\Interfaces;

interface IMigration {

    public static function up();
    public static function down();
    
}

?>