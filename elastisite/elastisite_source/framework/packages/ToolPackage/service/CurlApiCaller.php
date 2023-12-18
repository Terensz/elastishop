<?php

namespace framework\packages\ToolPackage\service;

class CurlApiCaller
{
    public $method = 'post';

    public $data = [];

    private $calledUrl;

    /**
     * If API endpoint requires auth. Works only if you also add password
    */
    public $username;

    /**
     * If API endpoint requires auth. Works only if you also add username
    */
    public $password;

    protected $header = [
        "Accept: application/json",
        "Content-Type: application/json",
        "Charset: utf-8"
    ];

    public $undecodedOutput;

    public $output;

    private $curlHandler;

    public function __construct()
    {
    }

    public function addHeaderElement($name, $value)
    {
        $this->header[] = $name.': '.$value;
    }

    /**
     * Elpostolt kulcs és érték hozzáadása
    */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function call($url)
    {
        // dump($url);exit;
        $this->curlHandler = curl_init();
        $ch = $this->curlHandler;
        
        if ($this->method == 'post') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($this->data != []) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
            }
        } else {
            if ($this->data != []) {
                $url .= '?' . http_build_query($this->data);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        }
        if ($this->username && $this->password)
        {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // dump($this);exit;
        $curlResult = curl_exec($ch);
        $this->undecodedOutput = $curlResult;
        $this->output = json_decode($curlResult, true);
        // dump(curl_exec($ch));exit;
        curl_close($ch);

        $this->calledUrl = $url;
    }

    public function getOutput()
    {
        return $this->output;
    }
} // end of class
