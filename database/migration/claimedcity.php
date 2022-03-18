#!/usr/bin/php -q
<?php

require_once __DIR__ . "/../../config/includes.php";
use App\Models\TibiaClaimedCity;

$city = array();

    $city['Inquisition'] = array(
        1 => "Hellfire Fighter and Spectres",
        2 => "The Vats Inquisition (Defilers)",
        3 => "The Blood Halls (Dt's)",
        4 => "Annihilon",
        5 => "Zugurosh"
    );

    $city['Carlin'] = array(
        6 => 'Demona Warlocks',
        7 => 'Cults of Tibia (Zathroth Remnants)',
        8 => 'Library Biting Books',
        9 => 'Library (Fire Area)',
        10 => 'Library (Energy Area)',
        11 => 'Library (Earth Area)',
        12 => 'Library (Ice Area)',
    );

    $city["Ab'Dendrie"] = array(
        15 => 'Barkless cave'
    );

    $city["Ankrahmun"] = array(
        20 => 'Mother of Scarabs Lair',
        21 => 'Scarabs Lair (Mountain Hidden)',
        22 => 'Otherworld Ankrahmun Area (Gold token)',
        23 => 'Nightmare Isles',
        24 => 'Nightmare Isles Middle Resp (Silencers)',
        25 => 'Cobra Bastion',
        26 => 'Cobra Bosses',
        27 => 'Cobra Sub Solo',
    );

    $city["Darashia"] = array(
        30 => 'Apocalypse (Jugger Seal)',
        31 => 'Ashfalor (Undead Seal)',
        32 => 'Bazir',
        33 => 'Infernatil (Fire Seal)',
        34 => 'Pumin',
        35 => 'Tafariel (Dt Seal)',
        36 => 'Verminor (Plague Seal)',
        37 => "Ferumbras' Castle",
        38 => 'Skeleton Elite Warrior (Inside)',
        39 => 'Skeleton Elite Warrior (Outside)',
        40 => 'Drefia Grim Reapers (Floor 1)',
        41 => 'Drefia Grim Reapers (Floor 1+2)',
        42 => 'Drefia Grim Reapers (Floor 2)',
        43 => 'Drefia Grim Reapers (Floor 2+3)',
        44 => 'Drefia Grim Reapers (Floor 3)',
        45 => 'Drefia Grim Reapers (Floor 1+2+3)',
        46 => 'Wyrms',
        47 => 'Elder Wyrms',
        48 => 'Dragon Darashia',
        49 => 'Dragon Lord Darashia',
        50 => 'Necromacer (Drefia)',
        51 => 'Vampire Hell',
        52 => "Lion's Rock",
        53 => 'Burster Spectre Tomb',
        54 => 'Werehyaena Cave Norte',
        55 => 'Werehyaena Cave Sul',
        56 => 'Werelion Cave -1',
        57 => 'Werelion Cave -2 West',
        58 => 'Werelion Cave -2 East'
    );

    $city["Edron"] = array(
        60 => 'EK Soils',
        61 => 'ED Soils',
        62 => 'MS Soils',
        63 => 'RP Soils',
        64 => 'Earth Elementals',
        65 => 'Earth Elementals -2 (Bog Raiders)',
        66 => 'Demons New (Demon Forge)',
        67 => 'Servant (Madmage)',
        68 => 'Cyclopolis Behemoths',
        69 => "Vampire's Crypt",
        70 => 'Hero Fortress -1',
        71 => 'Grimvale',
        72 => 'Lycanthrope Cave (Edron)',
        73 => 'Lycanthrope Cave (Cormaya)',
        74 => 'Falcon Bastion (Castle)',
        75 => 'Hero Fortress -2',
        76 => 'Zarganash -1',
        77 => 'Zarganash -2',
        78 => 'Ancient Lion Knight'
    );

    $city["Farmine"] = array(
        80 => 'Falcon Head (Oberon Area)',
        81 => 'Lizard City',
        82 => 'Draken Walls Nort',
        83 => 'Souleaters',
        84 => 'Corruption Hole (Old Chossens)',
        85 => 'New Chosens',
        86 => 'Ghastly Dragons (Souleaters)',
        87 => 'Ghastly Dragons Palace',
        88 => 'Draken Abominations (Scale)',
        89 => 'Drakens Abominations & Undead Dragons',
        90 => 'Stampor Cave',
        91 => 'Mortal Kombat (Isle of Strife)',
        92 => 'Muggy Plains',
        93 => 'Yielothax',
        94 => 'Draken Walls Sul',
        95 => 'GT Farmine'
    );

    $city["Goroma"] = array(
        100 => 'Goroma Hydra Surface',
        101 => 'Goroma SerpentsS. East East',
        102 => 'Goroma SerpentsS. West West',
        103 => 'Goroma SS MainFloor',
        104 => 'Bonebeast (Ramoa)',
        105 => 'Lich Hell',
        106 => 'Demons Goroma (Morgaroth area)'
    );

    $city["Gray Island"] = array(
        110 => 'Hive Surface',
        111 => 'The Hive Tower (North-east) (Stage 2)',
        112 => 'The Hive Tower (West) (Stage 3)',
        113 => 'The Hive Underground (Stage 3)',
        114 => 'Deathlings',
        115 => 'Deathlings -1'
    );

    $city["Liberty Bay"] = array(
        120 => 'Liberty Bay Wyrms +1',
        121 => 'Liberty Bay Wyrms +2',
        122 => 'Behemoths',
        123 => 'Wyrms (Depot East)',
        124 => 'Quaras South West',
        125 => 'Gargoyle Sanctuary (Meriana)'
    );

    $city["Port Hope"] = array(
        130 => 'Banuta MF',
        131 => 'Banuta -1',
        132 => 'Banuta -2',
        133 => 'Banuta -1 & -2',
        134 => 'Banuta -3',
        135 => 'Banuta -3 & -4',
        136 => 'Banuta -4',
        137 => 'Hydras Forbidden Land',
        138 => 'Hydras Mountain',
        139 => 'Medusa Tower',
        140 => 'New Giants Spider',
        141 => 'Water Elemental Old',
        142 => 'Water Elemental New (Massive)',
        143 => 'Asura Palace',
        144 => 'Asura New (After Mirror)',
        145 => 'True Asuris',
        146 => "Carnivora's Rocks -1",
        147 => "Carnivora's Rocks -2",
        148 => "Carnivora's Rocks -3",
        149 => 'Gazer Spectre Temple',
        150 => 'Zarganash (Port Hope)',
        151 => 'True Asuris -1',
        152 => 'True Asuris -2'
    );

    $city["Thais"] = array(
        160 => 'MoTA Extension (Fury)',
        161 => 'Minotaur Cults',
        162 => 'Minotaur Cults -1'
    );

    $city["Svargrond"] = array(
        170 => 'Okolnir Frost Dragons East',
        171 => 'OkolnirFrost Dragons West',
        172 => 'Sea Serpent New Mainfloor',
        173 => 'Sea Serpent New -1',
        174 => 'Sea Serpent Old Cave North',
        175 => 'Sea Serpent Old Cave South',
        176 => 'Svargrond Mines (Yakchal Floor)',
        177 => 'Old Crystal Spider (Mountain)',
        178 => 'New Crystal Spiders',
        179 => 'Crystal Spider Nibelor',
        180 => 'Ice Witch Temple',
        181 => 'Winter Court (Crazed Winters)',
        182 => 'GT Svargrond',
    );

    $city["Venore"] = array(
        200 => 'Venore Dragon Lords (POI)',
        201 => 'Ripper Spectre Cellar',
        202 => 'Buried Cathedral (Spectres and Arachnophobicas)',
        203 => 'Brain Grounds -1',
        204 => 'Brain Grounds -2',
        205 => 'Brain Grounds -3'
    );

    $city["Kazzordoon"] = array(
        211 => 'Upper Spike (lvl 49-)',
        212 => 'Lower Spike 1st Floor (80+)',
        213 => 'Lower Spike 2nd Floor (80+)',
        214 => 'Lower Spike 3rd Floor (80+)',
        215 => 'Diremaws North-East (Growth Task area)',
        216 => 'Diremaws South (Lost Exile area)',
        217 => 'Tunnel Tyrants (Warzone 5)',
        218 => 'Diremaws Nort (Warzone 6)',
        219 => 'Diremaws Sul (Warzone 6)',
        220 => 'Lava Lurker Nort(Warzone 4)',
        221 => 'Lava Lurker Sul(Warzone 4)',
    );

    $city["Krailos"] = array(
        225 => 'Nightmare scions',
        226 => 'Brimstone bug'
    );

    $city["Yalahar"] = array(
        230 => 'Yalahar Demons West',
        231 => 'Yalahar Demons East',
        232 => 'Fenrock Dragon Lords',
        233 => 'Grim Reapers Yalahar',
        234 => 'Sunken Quaras',
        235 => 'Vengoth Castle',
        236 => 'Cemetery -1',
        237 => 'Cults Yalahar',
        238 => 'Yalahar Dragons (NW)',
        239 => 'Yalahar Dragons (SE)',
        241 => 'Yalahar Dragons (Serpent)',
        242 => 'Hellspawn Surface',
        243 => 'Hellspawns -1',
        244 => 'Nightmares Yalahar',
        245 => 'Pirates Yalahar',
        246 => 'War Golems (Old Spawns)',
        247 => 'War Golems (New West)',
        248 => 'War Golems (New East)',
        249 => 'Netherworld'
    );

    $city["Roshamuul"] = array(
        260 => 'Roshamuul Lower (West)',
        261 => 'Roshamuul Lower (East)',
        262 => 'Roshamuul Bridge',
        263 => 'Guzzlemaw Valley (West)',
        264 => 'Guzzlemaw Valley (East)',
        265 => 'Roshamuul Prison -1',
        266 => 'Roshamuul Prison -2',
        267 => 'Roshamuul Prison -3',
        268 => 'Roshamuul Dp North',
        269 => 'Roshamuul Dp South',
        270 => 'Silencer Plateau',
    );

    $city["Feyrist"] = array(
        280 => 'Weakened Cave -1',
        281 => 'Weakened Cave -2',
        282 => 'Summer Courts (Crazed Summers)',
        283 => 'Weakened Moutain'
    );

    $city["Oramond"] = array(
        291 => 'East Minos Oramond',
        292 => 'North-East Minos Oramond',
        293 => 'West Oramond (Quaras+)',
        294 => 'Minos Oramond Entrance',
        295 => 'Glooth Factory',
        296 => 'Glooth Bandits West',
        297 => 'Glooth Bandits East',
        298 => 'Glooth Bandits South',
        299 => 'War Golems and Glooth Golems (500 pnts last room)',
        300 => 'Catacombs West',
        301 => 'Catacombs Middle',
        302 => 'Catacombs East',
        303 => 'Raid West (Elder Wyrms)',
        304 => 'Raid South (Golems)',
        305 => 'Raid East (Minotaurs)',
        306 => 'Abandoned Sewers (Demons and Grim Reapers)',
        307 => 'Rathleton Mangate (Furys and Spectres)',
        308 => 'Glooth Tower oramond'
    );

    $city["Issavi"] = array(
        400 => 'Bueiro de Issavi',
        401 => 'Labirinto de Issavi',
        600 => 'Issavi Sewers',
        601 => 'Issavi Mountains',
        602 => 'Issavi Ogres',
        603 => 'Issavi Goanna All',
        604 => 'Issavi Goanna East',
        605 => 'Issavi Goanna West',
        606 => 'Sphinx Cave All',
        607 => 'Sphinx Cave -1',
        608 => 'Sphinx Cave -2',
        609 => 'Sphinx Surface',
        610 => 'Issavi Catacombs',
        611 => 'Rotten Wasteland',
        612 => 'Furious Crater'
    );

    foreach ($city as $k => $v) {
        foreach ($v as $key => $value) {
            $claimedCity = new TibiaClaimedCity;
            $claimedCity->city = $k;
            $claimedCity->cod_city = $key;
            $claimedCity->respawn = $value;
            $claimedCity->save();
        }
    }