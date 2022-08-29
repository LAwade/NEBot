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
        'servertibia' => 'TIBIA',
        'conftibia' => 'TIBIA',
        'channelslist' => 'TIBIA',
        'friends' => "TIBIA",
        'hunteds' => 'TIBIA',
        'ally' => 'TIBIA',
        'enemy' => 'TIBIA',
        'claimed' => 'TIBIA',
        'rmclaimed' => 'TIBIA',
        'rashid' => 'TIBIA',
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

            'friends' => 'Adicionar/Remover/Listar da friends list! Adicionar: [Ex: !friends add GUILD,GUILD1] | Remover: [Ex: !friends rm GUILD,GUILD1] | Listar: [Ex: !friends]',
            'hunteds' => 'Adicionar/Remover/Listar da hunteds list! Adicionar: [Ex: !hunteds add GUILD,GUILD1] | Remover: [Ex: !hunteds rm GUILD,GUILD1] | Listar: [Ex: !hunteds]',
            'ally' => 'Adicionar/Remover players da ally list! Adicionar: [Ex: !ally add PLAYER,PLAYER1] | Remover: [Ex: !ally rm PLAYER,PLAYER1]',
            'enemy' => 'Adicionar/Remover players da enemy list! Adicionar: [Ex: !enemy add PLAYER,PLAYER1] | Remover: [Ex: !enemy rm PLAYER,PLAYER1]',

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

            'friends' => 'Add/Remove/List friend list! Add: [Ex: !friends add GUILD,GUILD1] | Remove: [Ex: !friends rm GUILD,GUILD1] | List: [Ex: !friends]',
            'hunteds' => 'Add/Remove/List hunteds list! Add: [Ex: !hunteds add GUILD,GUILD1] | Remove: [Ex: !hunteds rm GUILD,GUILD1] | List: [Ex: !hunteds]',
            'ally' => 'Add/Remove player to the ally list! Add: [Ex: !ally add PLAYER1,PLAYER2] | Remove: [Ex: !ally rm PLAYER1,PLAYER2]',
            'enemy' => 'Add/Remove player to the enemy list! Add: [Ex: !enemy add PLAYER1,PLAYER2] | Remove: [Ex: !enemy rm PLAYER1,PLAYER2]',

            'claimed' => 'Add the player to a respawn list. [Ex: !claimed COD_CITY PLAYER]',
            'rmclaimed' => 'Remove the player to a respawn list. [Ex: !rmclaimed PLAYER]',
        ]
    ]
];