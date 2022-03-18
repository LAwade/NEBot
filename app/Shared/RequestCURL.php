<?php

namespace App\Shared;

use Exception;

class RequestCURL
{

    private $curl;
    private $ssl;
    private $content;
    private $method;
    private $url;
    private $post_field;
    private $log;

    const CONF_TIMEOUT = 30;

    function __construct(bool $ssl = true)
    {
        $this->ssl = is_bool($ssl) ? $ssl : true;
    }

    public function content_type($content)
    {
        if (is_array($content)) {
            $this->content = $content;
        } else {
            $this->content = array($content);
        }
    }

    public function post_field($data, $json = false)
    {
        $this->method_request('POST');
        if ($json) {
            $this->post_field = $data;
        } else {
            $this->post_field = http_build_query($data);
        }
    }

    public function method_request($method = 'GET')
    {
        $this->method = strtoupper($method);
    }

    public function exec_request()
    {
        if (empty($this->url)) {
            throw new Exception('URL is required.');
        }

        if (!$this->method) {
            $this->method_request('GET');
        }

        $this->curl = curl_init($this->url);
        $this->conf_request();        
        $this->response = curl_exec($this->curl);
        
        if (curl_error($this->curl)) {
            throw new Exception("Error in cURL: " . curl_error($this->curl));
        }

        curl_close($this->curl);
        return $this->response;
    }

    public function convert_xml_to_object()
    {
        if (!$this->response) {
            return false;
        }
        $this->log->success(simplexml_load_string($this->response), debug_backtrace());
        return simplexml_load_string($this->response);
    }

    function setUrl($url): void
    {
        $this->url = $url;
    }

    ############################################################################
    ###                           CONFIG. CURL                               ###
    ############################################################################

    private function conf_request()
    {
        if ($this->content) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->content);
        }

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $this->ssl);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        if ($this->post_field) {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->post_field);
        }
        
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, self::CONF_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, self::CONF_TIMEOUT);
    }
}
