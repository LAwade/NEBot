<?php

return [
    "commands" => [
        'help' => 'TS',
        'mp' => 'TS',
        'mm' => 'TS',
        'mmoff' => 'TS',
        'mk' => 'TS',
        'mkoff' => 'TS',
        'mck' => 'TS',
        'mg' => 'TS',
        'mtg' => 'TS',
        'rps' => 'TS',
        'cgadmin' => 'TS',
        'rashid' => 'TIBIA',
        'servertibia' => 'TIBIA',
        'conftibia' => 'TIBIA',
        'channelslist' => 'TIBIA',
        'addfriend' => 'TIBIA',
        'friends' => "TIBIA",
        'rmfriend' => "TIBIA",
        'addhunted' => 'TIBIA',
        'hunteds' => 'TIBIA',
        'rmhunted' => 'TIBIA',
        'addally' => 'TIBIA',
        'rmally' => 'TIBIA',
        'addenemy' => 'TIBIA',
        'rmenemy' => 'TIBIA',
        'claimed' => 'TIBIA',
        'rmclaimed' => 'TIBIA',
    ],
    
    "translator" => [
        'pt' => [
            'help' => 'Apresenta todos os comandos disponíveis.',
            'mp' => 'Envia uma mensagem modal em massa para todos os usuários conectados. [Ex: %smp MENSAGEM]',
            'mm' => 'Move todos usuários para uma sala determinada no comando. [Ex: %smm SALA]',
            'mmoff' => 'Move todos usuários com status microfone e áudio desabilitado e ausente para uma sala específica. [Ex: %smmoff SALA]',
            'mk' => 'Expulsa todos usuários conectados do servidor.',
            'mkoff' => 'Expulsa todos usuários com status microfone e áudio desabilitado e ausente do servidor.',
            'mck' => 'Expulsa todos usuários da sala.',
            'mg' => 'Move participantes de um grupo para uma sala específica. [Ex: %smg SALA GRUPO]',
            'mtg' => 'Move participantes de um grupo para a sala de chamada. [Ex: %smtg GRUPO]',
            'rps' => 'Remove clientes com Priority Speaker ativo.',
            'cgadmin' => 'Cria os grupos de utilização dos comandos do BOT.',

            'rashid' => 'Localiza o Rashid no servidor Global.',
            'servertibia' => 'Lista todos servidores de Tibia.',
            'conftibia' => 'Registra o servidor de Tibia que será configurado no BOT.',
            'channelslist' => 'Cria/Atualiza as salas de Tibia.',

            'addfriend' => 'Adiciona uma ou várias guilds a friend list. [Ex: !addfriend GUILD] ou [Ex: !addfriend GUILD1, GUILD2]',
            'friends' => 'Apresenta todos os friends cadastrados!',
            'rmfriend' => 'Remove um ou várias guilds a friend list. [Ex: !rmfriend GUILD] ou [Ex: !rmfriend GUILD1, GUILD2]',

            'addhunted' => 'Adiciona uma várias guild a hunted list. [Ex: !addhunted GUILD] ou [Ex: !addhunted GUILD1, GUILD2]',
            'hunteds' => 'Show all registered hunteds!',
            'rmhunted' => 'Remove one or more guilds from the hunted list. [Ex: !rmhunted GUILD] or [Ex: !rmhunted GUILD1, GUILD2]',

            'addally' => 'Adiciona um ou vários players a ally list. [Ex: !addally PLAYER] ou [Ex: !addfriend PLAYER1, PLAYER2]',
            'rmally' => 'Remove um ou vários players da ally list. [Ex: !rmally PLAYER] or [Ex: !rmally PLAYER1, PLAYER2]',

            'addenemy' => 'Adiciona uma vários player a enemy list. [Ex: !addenemy PLAYER] ou [Ex: !addfriend PLAYER1, PLAYER2]',
            'rmenemy' => 'Remove um ou vários players da enemy list. [Ex: !rmenemy PLAYER] or [Ex: !rmenemy PLAYER1, PLAYER2]',
            'claimed' => 'Adiciona o player a um lista de respawn. [Ex: !claimed COD_CITY PLAYER]',
            'rmclaimed' => 'Remove o player a um lista de respawn. [Ex: !rmclaimed PLAYER]',
        ],
        'en' => [
            'help' => 'Displays all available commands.',
            'mp' => 'Sends a bulk modal message to all connected users. [Ex: %smp MESSAGE]',
            'mm' => 'Move all users to a given room on command. [Ex: %smm CHANNEL]',
            'mmoff' => 'Moves all users with mic and audio disabled and absent status to a specific room. [Ex: %smmoff CHANNEL]',
            'mk' => 'Kicks all connected users from the server.',
            'mkoff' => 'Kicks all users with mic and audio disabled status and absent from the server.',
            'mck' => 'Kicks all users out of the channel.',
            'mg' => 'Move participants from a group to a specific channel. [Ex: %smg CHANNEL GROUP]',
            'mtg' => 'Move participants from a group to the call room. [Ex: %smtg GROUP]',
            'rps' => 'Remove clients with Priority Speaker active.',
            'cgadmin' => 'Creates the BOT command usage groups.',

            'rashid' => 'Location Rashid on the Global server.',
            'servertibia' => 'List all servers Tibia.',
            'conftibia' => 'Register the Tibia server that will be configured in the BOT.',
            'channelslist' => 'Create/Update channels Tibia.',

            'addfriend' => 'Add one or mutiple guilds to a friend list. [Ex:! Addfriend GUILD] or [Ex:! Addfriend GUILD1, GUILD2]',
            'friends' => 'Show all registered friends!',
            'rmfriend' => 'Remove one or more guilds from the friend list. [Ex: !rmfriend GUILD] or [Ex: !rmfriend GUILD1, GUILD2]',

            'addhunted' => 'Add one or mutiple guilds to hunted list. [Ex: !addhunted GUILD] or [Ex: !addhunted GUILD1, GUILD2]',
            'hunteds' => 'Show all registered hunteds!',
            'rmhunted' => 'Remove one or more guilds from the hunted list. [Ex: !rmhunted GUILD] or [Ex: !rmhunted GUILD1, GUILD2]',

            'addally' => 'Adds a multiple player to the ally list. [Ex: !addally PLAYER] or [Ex: !addfriend PLAYER1, PLAYER2]',
            'rmally' => 'Remove one or more allys from the ally list. [Ex: !rmally PLAYER] or [Ex: !rmally PLAYER1, PLAYER2]',

            'addenemy' => 'Adds a multiple player to the enemy list. [Ex: !addenemy PLAYER] ou [Ex: !addfriend PLAYER1, PLAYER2]',
            'rmenemy' => 'Remove one or more enemys from the enemy list. [Ex:!rmenemy PLAYER] or [Ex:!rmenemy PLAYER1, PLAYER2]',
            'claimed' => 'Add the player to a respawn list. [Ex: !claimed COD_CITY PLAYER]',
            'rmclaimed' => 'Remove the player to a respawn list. [Ex: !rmclaimed PLAYER]',
        ]
    ]
];