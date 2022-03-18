<?php

require_once __DIR__ . "/../../config/includes.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Interfaces\IMigration;

class CreateTeamspeakTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('teamspeaks', function ($table) {
            $table->increments('id');
            $table->string('host', 50);
            $table->string('port', 10);
            $table->string('querylogin', 50);
            $table->string('querypassword', 200);
            $table->string('queryport', 10);
            $table->integer('fk_id_bot');
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->foreign('fk_id_bot')
                ->references('id')->on('bots')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('teamspeaks');
    }
}

class CreateBotsTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('bots', function ($table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->string('name')->default('BEXP');
            $table->string('tag_command', 1)->default('!');
            $table->integer('level_tibia')->default(350);
            $table->integer('sgid_claimed')->nullable();
            $table->integer('sgid_bot')->nullable();
            $table->integer('limit_friend')->nullable();
            $table->integer('limit_hunted')->nullable();
            $table->integer('limit_ally')->nullable();
            $table->integer('limit_enemy')->nullable();
            $table->integer('external_id')->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('bots');
    }
}

class CreateTSBOTMoveTable implements IMigration{
    public static function up(){
        Capsule::schema()->create('tsbot_move', function ($table) {
            $table->increments('id');
            $table->integer('channel');
            $table->integer('timer');
            $table->string('status', 10);
            $table->integer('fk_id_bot');
            $table->string('queryport', 10);
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->foreign('fk_id_bot')
                ->references('id')->on('bots')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tsbot_move');
    }
}

class CreateTibiaApiServerTable implements IMigration{
    public static function up(){
        Capsule::schema()->create('tibia_api_server', function ($table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('host', 250);
            $table->string('token', 250)->nullable();
            $table->string('userapi', 50)->nullable();
            $table->string('password', 50)->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_api_server');
    }
}

class CreateTibiaApiTable implements IMigration{
    public static function up(){
        Capsule::schema()->create('tibia_api', function ($table) {
            $table->increments('id');
            $table->string('server', 50);
            $table->string('src', 100);
            $table->string('url', 250);
            $table->integer('premium')->default(0);
            $table->integer('fk_id_tibia_api_server');
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_api');
    }
}

class CreateTibiaTable implements IMigration{
    public static function up(){
        Capsule::schema()->create('tibia', function ($table) {
            $table->increments('id');
            $table->string('world', 50)->nullable();
            $table->integer('fk_id_bot');
            $table->integer('fk_id_tibia_api');
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->foreign('fk_id_bot')
                ->references('id')->on('bots')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia');
    }
}

class CreateTibiaChannelTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_channel', function ($table) {
            $table->increments('id');
            $table->integer('friend_high_channel')->nullable();
            $table->integer('friend_low_channel')->nullable();
            $table->integer('hunted_high_channel')->nullable();
            $table->integer('hunted_low_channel')->nullable();
            $table->integer('neutral_channel')->nullable();
            $table->integer('ally_channel')->nullable();
            $table->integer('enemy_channel')->nullable();
            $table->integer('death_channel')->nullable();
            $table->integer('claimed_channel')->nullable();
            $table->integer('news_channel')->nullable();
            $table->integer('fk_id_tibia');
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_channel');
    }
}

class CreateTibiaFriendListTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_friend_list', function ($table) {
            $table->increments('id');
            $table->string('tibia_guild', 50);
            $table->integer('fk_id_tibia');
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_friend_list');
    }
}

class CreateTibiaHuntedListTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_hunted_list', function ($table) {
            $table->increments('id');
            $table->string('tibia_guild', 50);
            $table->integer('fk_id_tibia');
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_hunted_list');
    }
}

class CreateTibiaAllyListTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_ally_list', function ($table) {
            $table->increments('id');
            $table->string('player_ally', 50);
            $table->integer('fk_id_tibia');
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_ally_list');
    }
}

class CreateTibiaEnemyListTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_enemy_list', function ($table) {
            $table->increments('id');
            $table->string('player_enemy', 50);
            $table->integer('fk_id_tibia');
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_enemy_list');
    }
}

class CreateTibiaClaimedListTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_claimed_city', function ($table) {
            $table->increments('id');
            $table->integer('cod_city');
            $table->string('city', 50);
            $table->string('respawn', 150);
            $table->timestamps();
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_claimed_city');
    }
}

class CreateTibiaClaimedPlayerTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('tibia_claimed_player', function ($table) {
            $table->increments('id');
            $table->string('player', 150);
            $table->integer('cldbid');
            $table->integer('fk_id_tibia');
            $table->integer('fk_id_claimed_city');
            $table->timestamps();
            $table->foreign('fk_id_tibia')
                ->references('id')->on('tibia')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('tibia_claimed_player');
    }
}

class CreateNotificationTable implements IMigration{

    public static function up(){
        Capsule::schema()->create('notifications', function ($table) {
            $table->increments('id');
            $table->integer('fk_id_bot');
            $table->timestamp('deaths')->nullable();
            $table->timestamp('claimed')->nullable();
            $table->timestamp('move')->nullable();
            $table->timestamp('message')->nullable();
            $table->timestamps();
            $table->foreign('fk_id_bot')
                ->references('id')->on('bots')
                ->onDelete('cascade');
        });
    }
    
    public static function down(){
        Capsule::schema()->dropIfExists('notifications');
    }
}

$up = function () {
    CreateBotsTable::up();
    CreateTeamspeakTable::up();
    CreateTSBOTMoveTable::up();
    CreateTibiaApiServerTable::up();
    CreateTibiaApiTable::up();
    CreateTibiaTable::up();
    CreateTibiaChannelTable::up();
    CreateTibiaFriendListTable::up();
    CreateTibiaHuntedListTable::up();
    CreateTibiaAllyListTable::up();
    CreateTibiaEnemyListTable::up();
    CreateTibiaClaimedListTable::up();
    CreateTibiaClaimedPlayerTable::up();
    CreateNotificationTable::up();
};

$down = function (){
    CreateTeamspeakTable::down();
    CreateTSBOTMoveTable::down();
    CreateTibiaApiServerTable::down();
    CreateTibiaApiTable::down();
    CreateTibiaChannelTable::down();
    CreateTibiaFriendListTable::down();
    CreateTibiaHuntedListTable::down();
    CreateTibiaAllyListTable::down();
    CreateTibiaEnemyListTable::down();
    CreateTibiaClaimedListTable::down();
    CreateTibiaClaimedPlayerTable::down();
    CreateNotificationTable::down();
    CreateTibiaTable::down();
    CreateBotsTable::down();
};

$down();
$up();