<?php

    namespace App\Providers;

    class TibiaBOT {

        private function friends($dataFriends) {
            $friends = $dataFriends;
            $total = 0;
            $online_high = 0;
            $online_low = 0;

            if (!$dataFriends) {
                return false;
            }

            foreach ($dataFriends as $key => $guild) {
                if (!count($guild)) {
                    continue;
                }

                foreach ($guild as $friend) {
                    if (strtolower($friend['status']) == 'online') {
                        if ($friend['level'] >= $this->tibiainfo->param_level_tibia) {
                            $friend['vocation'] = $this->vocation($friend['vocation']);
                            if ($online_high < self::LIMITE_DESCRIPTION) {
                                $description_high .= "[tr][td][{$friend['vocation']}][/td] [td][COLOR=GREEN]{$friend['name']}[/COLOR][/td] [td][+{$friend['level']}][/td] [td][{$this->nameguild($key)}][/td][/tr]";
                            }
                            $online_high++;
                        } else {
                            $friend['vocation'] = $this->vocation($friend['vocation']);
                            if ($online_low < self::LIMITE_DESCRIPTION) {
                                $description_low .= "[tr][td][{$friend['vocation']}][/td] [td][COLOR=GREEN]{$friend['name']}[/COLOR][/td] [td][+{$friend['level']}][/td] [td][{$this->nameguild($key)}][/td][/tr]";
                            }
                            $online_low++;
                        }
                    }
                    $total++;
                }
            }
            $description_high .= "[/table]";
            $description_low .= "[/table]";

            //$this->channelEditList($this->tibia->friend_high_channel, $description_high, $online_high, "Friend List [+{$this->tibiainfo->param_level_tibia}]", $total);
            //$this->channelEditList($this->tibia->friend_low_channel, $description_low, $online_low, "Friend List [-{$this->tibiainfo->param_level_tibia}]", $total);
            return ['friend_high' => $description_high, 'friend_low' => $description_low];
        }

        private function ally() {
            $description = "[table]";
            $online = 0;
            $response = $this->allylist->findByTibia($this->tibia->id_tibia);
            $onlines = $this->dataOnlines();
            $added = false;
            if ($response && $onlines) {
                foreach ($response as $ally) {
                    foreach ($onlines as $value) {
                        if (str_scape_html($ally->player_ally) == $value['name']) {
                            $this->playerlist['ally'][] = $value;
                            $description .= "[tr][td][" . $this->vocation($value['vocation']) . "][/td] [td][COLOR=GREEN]{$value['name']}[/COLOR][/td] [td][+{$value['level']}][/td][/tr]";
                            $added = true;
                            $online++;
                        }
                    }
                    if ($added) {
                        $added = false;
                    } else {
                        $description .= sprintf("[tr][td] [/td][td][COLOR=#808080]%s[/COLOR][/td] [td][COLOR=RED][Offline][/COLOR][/td][/tr]", str_scape_html($ally->player_ally));
                    }
                }
            }
            $description .= "[/table]";
            $this->channelEditList($this->tibia->ally_channel, $description, $online, 'Ally List', count($response));
        }

        private function hunteds() {
            $this->hunteds = $this->dataHunteds();
            $description = "[table]";
            $onlines = 0;
            $total = 0;

            $descriptionmaker = "[table]";
            $onlinesmaker = 0;
            $totalmaker = 0;

            if (!$this->hunteds) {
                return false;
            }

            foreach ($this->hunteds as $key => $guild) {
                if (!count($guild)) {
                    continue;
                }
                
                foreach ($guild as $player) {
                    if ($player['level'] >= $this->tibiainfo->param_level_tibia) {
                        if (strtolower($player['status']) == 'online') {
                            if ($onlines < self::LIMITE_DESCRIPTION_HUNTED) {
                                $description .= sprintf("[tr][td][%s][/td] [td][COLOR=RED]{$player['name']}[/COLOR][/td] [td][+{$player['level']}][/td] [td][{$this->nameguild($key)}][/td][/tr]", $this->vocation($player['vocation']));
                            }
                            $onlines++;
                        }
                        $total++;
                    } else {
                        if (strtolower($player['status']) == 'online') {
                            if ($onlinesmaker < self::LIMITE_DESCRIPTION_HUNTED) {
                                $descriptionmaker .= sprintf("[tr][td][%s][/td] [td][COLOR=RED]{$player['name']}[/COLOR][/td] [td][+{$player['level']}][/td] [td][{$this->nameguild($key)}][/td][/tr]", $this->vocation($player['vocation']));
                            }
                            $onlinesmaker++;
                        }
                        $totalmaker++;
                    }
                }
            }
            $description .= "[/table]";
            $descriptionmaker .= "[/table]";
            $this->channelEditList($this->tibia->hunted_high_channel, $description, $onlines, "Hunted List[+{$this->tibiainfo->param_level_tibia}]", $total);
            $this->channelEditList($this->tibia->hunted_low_channel, $descriptionmaker, $onlinesmaker, "Hunted List[-{$this->tibiainfo->param_level_tibia}]", $totalmaker);
        }

        private function enemy() {
            $description = "[table]";
            $online = 0;
            $added = false;
            $response = $this->enemylist->findByTibia($this->tibia->id_tibia);
            $onlines = $this->dataOnlines();

            if ($response && $onlines) {
                foreach ($response as $enemy) {
                    foreach ($onlines as $value) {
                        if (str_scape_html($enemy->player_enemy) == $value['name']) {
                            $this->playerlist['enemy'][] = $value;
                            $description .= "[tr][td][" . $this->vocation($value['vocation']) . "][/td] [td][COLOR=RED]{$value['name']}[/COLOR][/td] [td][+{$value['level']}][/td][/tr]";
                            $online++;
                            $added = true;
                        }
                    }
                    if ($added) {
                        $added = false;
                    } else {
                        $description .= sprintf("[tr][td][/td] [td][COLOR=#808080]%s[/COLOR][/td] [td][COLOR=RED][Offline][/COLOR][/td][/tr]", str_scape_html($enemy->player_enemy));
                    }
                }
            }
            $description .= "[/table]";
            $this->channelEditList($this->tibia->enemy_channel, $description, $online, 'Enemy List', count($response));
        }

        private function neutrals() {
            $description = "[table]";
            $total = 0;
            $totalon = 0;

            $onlines = $this->dataOnlines();

            if (!$onlines) {
                return null;
            }

            foreach ($onlines as $neutrals) {
                $total++;
                $totalon++;

                if ($total < self::LIMITE_DESCRIPTION) {
                    $description .= sprintf("[tr][td][%s][/td][td][COLOR=BLUE]{$neutrals['name']}[/COLOR][/td] [td][+{$neutrals['level']}][/td][/tr]", $this->vocation($neutrals['vocation']));
                }
            }
            $description .= "[/table]";
            $this->channelEditList($this->tibia->neutral_channel, $description, $total, 'Neutral List', $totalon);
        }

        private function deaths() {
            $deaths = 0;
            $total = 0;
            $description = "[table]";
            $message = "[B][DEATH] " . json_decode('"\uD83D\uDC80"') . "[COLOR=%s]  %s [/COLOR]MORTO [COLOR=BLUE][%s] %s[/COLOR] [+%s][COLOR=#ff3300] %s [/COLOR][COLOR=#6600ff][%s][/COLOR][/B]";
            $deathslist = $this->dataDeaths();

            $friends = $this->dataArray($this->friends);
            $hunteds = $this->dataArray($this->hunteds);
            foreach ($deathslist as $players) {

                foreach ($friends as $value) {
                    if ($players['name'] == $value['name']) {

                        if ($deaths < self::LIMITE_DESCRIPTION) {
                            $description .= "[tr][td][" . $this->vocation($value['vocation']) . "][/td] [td][COLOR=GREEN]{$value['name']}[/COLOR][/td] [td][+{$value['level']}][/td] [td][ " . date('d/m/Y H:i:s', strtotime($players['hours'])) . " ][/td][/tr]";
                        }

                        if (strtotime($players['hours']) > $this->getInfo('deaths') && $this->getInfo('deaths')) {
                            $msg = sprintf($message, 'GREEN', 'FRIEND', $this->vocation($value['vocation']), $value['name'], $value['level'], str_replace($value['name'], "", $players['reason']), date('d/m/Y H: i: s', strtotime($players['hours'])));
                            $this->messageServer($msg);
                        }
                        $deaths++;
                    }
                }

                foreach ($hunteds as $value) {
                    if ($players['name'] == $value['name']) {

                        if ($deaths < self::LIMITE_DESCRIPTION) {
                            $description .= "[tr][td][" . $this->vocation($value['vocation']) . "][/td] [td][COLOR=RED]{$value['name']}[/COLOR][/td] [td][+{$value['level']}][/td] [td][ " . date('d/m/Y H:i:s', strtotime($players['hours'])) . " ][/td][/tr]";
                        }

                        if (strtotime($players['hours']) > $this->getInfo('deaths') && $this->getInfo('deaths')) {
                            $msg = sprintf($message, 'RED', 'HUNTED', $this->vocation($value['vocation']), $value['name'], $value['level'], str_replace($value['name'], "", $players['reason']), date('d/m/Y H: i: s', strtotime($players['hours'])));
                            $this->messageServer($msg);
                        }
                        $deaths++;
                    }
                }

                foreach ($this->playerlist['ally'] as $ally) {
                    if ($players['name'] == $ally['name']) {

                        if ($deaths < self::LIMITE_DESCRIPTION) {
                            $description .= "[tr][td][" . $this->vocation($ally['vocation']) . "][/td] [td][COLOR=GREEN]{$ally['name']}[/COLOR][/td] [td][+{$ally['level']}][/td] [td][ " . date('d/m/Y H:i:s', strtotime($players['hours'])) . " ][/td][/tr]";
                        }

                        if (strtotime($players['hours']) > $this->getInfo('deaths') && $this->getInfo('deaths')) {
                            $msg = sprintf($message, 'GREEN', 'ALLY', $this->vocation($players['vocation']), $ally['name'], $ally['level'], str_replace($ally['name'], "", $players['reason']), date('d/m/Y H: i: s', strtotime($players['hours'])));
                            $this->messageServer($msg);
                        }
                        $deaths++;
                    }
                }

                foreach ($this->playerlist['enemy'] as $enemy) {
                    if ($players['name'] == $enemy['name']) {

                        if ($deaths < self::LIMITE_DESCRIPTION) {
                            $description .= "[tr][td][" . $this->vocation($enemy['vocation']) . "][/td] [td][COLOR=RED]{$enemy['name']}[/COLOR][/td] [td][+{$enemy['level']}][/td] [td][ " . date('d/m/Y H:i:s', strtotime($players['hours'])) . " ][/td][/tr]";
                        }

                        if (strtotime($players['hours']) > $this->getInfo('deaths') && $this->getInfo('deaths')) {
                            $msg = sprintf($message, 'RED', 'ENEMY', $this->vocation($players['vocation']), $enemy['name'], $enemy['level'], str_replace($enemy['name'], "", $players['reason']), date('d/m/Y H: i: s', strtotime($players['hours'])));
                            $this->messageServer($msg);
                        }
                        $deaths++;
                    }
                }
                $total++;
            }
            $description .= "[/table]";
            $this->channelEditList($this->tibia->death_channel, $description, $deaths, 'Deaths List', $total);
        }

        private function uplevel() {
            $uplevel = $this->dataLevelup()[0];
            $friends = $this->dataArray($this->friends);
            $hunteds = $this->dataArray($this->hunteds);

            if ($uplevel) {
                $huntedup = $this->diffLevel($uplevel, $hunteds);
                if ($huntedup) {
                    foreach ($huntedup as $value) {
                        $this->messageServer("[B][UP LEVEL]" . json_decode('"\u2B06\uFE0F"') . "[COLOR=RED]  HUNTED [/COLOR][COLOR=BLUE][" . $this->vocation($value['vocation']) . "][/COLOR][COLOR=#9900ff] {$value['name']}[/COLOR] Subiu de Level para [+{$value['level']}][/B]");
                    }
                }

                $friendsup = $this->diffLevel($uplevel, $friends);
                if ($friendsup) {
                    foreach ($friendsup as $value) {
                        $this->messageServer("[B][UP LEVEL]" . json_decode('"\u2B06\uFE0F"') . "[COLOR=GREEN]  FRIEND [/COLOR][COLOR=BLUE][" . $this->vocation($value['vocation']) . "][/COLOR][COLOR=#9900ff] {$value['name']}[/COLOR] Subiu de Level para [+{$value['level']}][/B]");
                    }
                }

                $allyup = $this->diffLevel($uplevel, $this->playerlist['ally']);
                if ($allyup) {
                    foreach ($allyup as $value) {
                        $this->messageServer("[B][UP LEVEL]" . json_decode('"\u2B06\uFE0F"') . "[COLOR=GREEN]  ALLY [/COLOR][COLOR=BLUE][" . $this->vocation($value['vocation']) . "][/COLOR][COLOR=#9900ff] {$value['name']}[/COLOR] Subiu de Level para [+{$value['level']}][/B]");
                    }
                }

                $enemyup = $this->diffLevel($uplevel, $this->playerlist['enemy']);
                if ($enemyup) {
                    foreach ($enemyup as $value) {
                        $this->messageServer("[B][UP LEVEL]" . json_decode('"\u2B06\uFE0F"') . "[COLOR=RED]  ENEMY [/COLOR][COLOR=BLUE][" . $this->vocation($value['vocation']) . "][/COLOR][COLOR=#9900ff] {$value['name']}[/COLOR] Subiu de Level para [+{$value['level']}][/B]");
                    }
                }
            }
        }

        private function charconnect() {
            $message = "[B][CHAR CONNECT][COLOR=%s] %s [/COLOR] Conectou no Jogo [COLOR=BLUE][%s] %s[/COLOR] [+%s] [COLOR=GREEN][Online][/COLOR][/B]";
            $connects = $this->dataConnect();
            $friends = $this->dataArray($this->friends);
            $hunteds = $this->dataArray($this->hunteds);
            
            foreach ($connects as $players) {
                foreach ($friends as $value) {
                    if ($players == $value['name']) {
                        $msg = sprintf($message, 'GREEN',  '  FRIEND', $this->vocation($value['vocation']), $value['name'], $value['level']);
                        $this->messageServer($msg);
                    }
                }

                foreach ($hunteds as $value) {
                    if ($players == $value['name']) {
                        $msg = sprintf($message, 'RED',  '  HUNTED', $this->vocation($value['vocation']), $value['name'], $value['level']);
                        $this->messageServer($msg);
                    }
                }

                foreach ($this->playerlist['ally'] as $ally) {
                    if ($players == $this->charName($ally['name'])) {
                        $msg = sprintf($message, 'GREEN',  '  ALLY', $this->vocation($ally['vocation']), $ally['name'], $ally['level']);
                        $this->messageServer($msg);
                    }
                }

                foreach ($this->playerlist['enemy'] as $enemy) {
                    if ($players == $this->charName($enemy['name'])) {
                        $msg = sprintf($message, 'RED',  '  ENEMY', $this->vocation($enemy['vocation']), $enemy['name'], $enemy['level']);
                        $this->messageServer($msg);
                    }
                }
            }
        }

        private function news($msg) {
            $limit = 10;
            $description = $this->tibiabot->channelInfo($this->tibia->news_channel)['data']['channel_description'];
            $c = 1;

            $separetor = "[COLOR=#cccccc]------------------------------------------------------------------------------------------------------------[/COLOR]";
            if ($this->tibia->news_channel) {
                $data = explode('\n', $description);
                $desc = "[" . date_hours_br(time(), true) . "] - " . $msg . "\n";
                for ($x = 2; $x < (count($data)); $x++) {
                    if ($data[$x] && $c < $limit && $data[$x] != $separetor) {
                        $desc .= $separetor . "\n";
                        $desc .= $data[$x] . "\n";
                        $c++;
                    }
                }
                $this->channelEditList($this->tibia->news_channel, $desc, $c++, 'News', $limit);
            }
        }

        private function claimed() {
            $claimed = $this->claimedplayer->findAllByTibia($this->tibia->id_tibia);
            $count = 0;
            foreach ($claimed as $val) {
                $city = $this->claimedcity->findByIdRespawn($val->fk_id_claimed_city);

                if (time() >= strtotime($val->register_claimed_player . ' +2 hours')) {
                    $this->claimedplayer->remove($val->id_claimed_player);
                } else if ($count < self::LIMITE_DESCRIPTION) {
                    $des[] = [$val->player_claimed, $city->city_claimed, $city->respawn_claimed, $val->register_claimed_player, $val->register_claimed_player];
                    $description .= "[tr][td]Player: [COLOR=BLUE][" . $val->player_claimed . "][/COLOR][/td] [td]City: [COLOR=GREEN]{$city->city_claimed}[/COLOR][/td] [td]Respawn: [COLOR=PURPLE][{$city->respawn_claimed}][/td] [td]Entrou: [COLOR=GREEN][ " . date_hours_br($val->register_claimed_player) . " ][/COLOR][/td][td]Termina: [COLOR=RED][ " . date_hours_br_incrase($val->register_claimed_player, '+2 hours') . " ][/COLOR][/td][/tr]";
                }
                $count++;
            }
            $this->channelEditList($this->tibia->claimed_channel, $description, $count, 'Claimed', count($claimed));
        }

        private function clear() {
            unset($this->data);
            unset($this->hunteds);
            unset($this->friends);
            unset($this->playerlist);
            $this->cleanData();
        }
    }