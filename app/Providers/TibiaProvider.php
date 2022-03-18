<?php

namespace App\Providers;

use App\Interfaces\ITibia;
use App\Shared\RequestCURL;
use Exception;

class TibiaProvider implements ITibia
{

    /**
     * Retorna as players friends
     * 
     * @return array|boolean
     */

    function dataGuilds($world, $src, $server, $url, $host, $guild)
    {
        $host = "http://{$host}:3003/api/v2/list/guilds";
        $request = [];
        $request['world'] = ($world ?? 'opentibia');
        $request['src'] = $src;
        $request['server'] = $server;
        $request['guild'] = $guild;
        $request['url'] = $url;
        $response[] = $this->setRequest($host, json_encode($request));
        return $this->converJsonArray($response);
    }

    /**
     * Retorna os dados dos Deaths no Servidor
     * 
     * @return boolean
     */
    function dataNeutrals($world, $src, $server, $url, $host)
    {
        $response = [];
        $host = "http://{$host}:3003/api/v2/list/neutrals";
        $request = [];
        $request['world'] = ($world ?? 'opentibia');
        $request['src'] = $src;
        $request['server'] = $server;
        $request['url'] = $url;
        $response[] = $this->setRequest($host, json_encode($request));

        return $this->converJsonArray($response);
    }

    /**
     * Retorna os dados dos Deaths no Servidor
     * 
     * @return boolean
     */
    function dataDeaths($world, $src, $server, $url, $host)
    {
        $response = [];
        $host = "http://{$host}:3003/api/v2/list/deaths";
        $request = [];
        $request['world'] = ($world ?? 'opentibia');
        $request['src'] = $src;
        $request['server'] = $server;
        $request['url'] = $url;
        $response[] = $this->setRequest($host, json_encode($request));
        return $this->converJsonArray($response);
    }

    private function setRequest($url, $params)
    {
        try {
            $request = new RequestCURL(false);
            $request->setUrl($url);
            $request->content_type(array("Content-type: application/json", "Accept: application/json"));
            $request->post_field($params, true);
            return $request->exec_request();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    ########################################################################
    #####                     FUNCOES UTILITARIAS                      #####
    ########################################################################

    private function converJsonArray($data)
    {
        $format = [];
        foreach ($data as $json) {
            $format[] =  json_decode($json, true);
        }
        return $format;
    }

    private function convertData($data)
    {
        $convert = json_decode($data, true);
        return $convert['data'];
    }

    /**
     * Orderna array por level
     * @param type $data
     * @return type
     */
    private function orderby($data)
    {
        // usort($data, function($a, $b) {
        //     return $a['level'] < $b['level'];
        // });
        // return $data;
    }

    /**
     * Retorna o uma nome para guild definidos
     * @param string $guild
     * @return string
     */
    protected function guildname($guild)
    {
        return strtolower(str_replace(' ', '+', $guild));
    }

    /**
     * Compara entre arrays diferença.
     * 
     * $array1 = Array Antigo
     * $array2 = Array Novo
     * retorno array a diferença com o novo $array2
     * 
     * @param array $array1
     * @param array $array2
     * @param string $indice
     * @return array
     */
    protected function diffArray($array1, $array2, $indice)
    {
        foreach ($array1 as $diff) {
            $tmp1[] = $diff[$indice];
        }

        foreach ($array2 as $diff) {
            $tmp2[] = $diff[$indice];
        }

        return array_diff($tmp2, $tmp1);
    }

    protected function diffLevel($array1, $array2)
    {
        $arrayDiff = array_filter($array1, function ($element) use ($array2) {
            $array = [];
            foreach ($array2 as $value) {
                if ($element['name'] == $value['name']) {
                    $array[] = $value;
                }
            }
            return $array;
        });
        return $arrayDiff;
    }

    /**
     * Transforma um array multidimencional em array simples
     * @param type $data
     * @return array
     */
    function dataArray($data)
    {
        $friends = array();
        foreach ($data as $guild) {
            foreach ($guild as $char) {
                array_push($friends, $char);
            }
        }
        return $friends;
    }

    /**
     * Transforma um array multidimencional em array simples
     * @param type $data
     * @return array
     */
    function dataObjectArray($data, $indice)
    {
        $guilds = array();
        foreach ($data as $g) {
            array_push($guilds, $this->guildname($g->$indice));
        }
        return $guilds;
    }

    protected function nameguild($name)
    {
        return ucwords(str_replace('+', ' ', $name));
    }

    /**
     * Reduz o vocation do personagem para 2 letras iniciais de cada classe.
     * @param string $vocation
     * @return string
     */
    private function vocation($vocation)
    {
        if (strpos($vocation, " ") !== false) {
            $str = explode(" ", $vocation);
            return strtoupper(substr($str[0], 0, 1) . substr($str[1], 0, 1));
        } else {
            return strtoupper(substr($vocation, 0, 2));
        }
    }
}
