<?php


namespace App\Http\Libraries\Request;


class Request
{
    /**
     * method of request
     *
     * @var string GET
     */
    private $method = 'GET';

    /**
     * mapping method string to int;
     * @var array
     */
    private $methodMap = ['POST' => 1, 'GET' => 0];

    /**
     * timeout request
     *
     * @var int 3
     */
    private $timeout = 3;

    /**
     * result of request
     *
     * @var string
     */
    private $result;

    /**
     * url endpoint
     *
     * @var string
     */
    private $url;

    /**
     * form data of request
     *
     * @var array
     */
    private $fieldData = [];

    /**
     * create new instance Request.
     *
     * @param $url
     * @param string $method
     * @param array $data
     * @param int $timeout
     */
    public function __construct($url, $method = 'GET', array $data = [], $timeout = 3)
    {
        $this->url = $url;
        $this->method = in_array($method, ['POST', 'GET']) ? $method : 'GET';
        $this->fieldData = $data;
        $this->timeout = $timeout;
    }

    /**
     * send request via CURL php
     *
     */
    private function sendRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, $this->methodMap[$this->method]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $this->result = curl_exec($ch);

        curl_close($ch);
    }

    /**
     * run send request and return data
     *
     * @return mixed
     */
    public function get()
    {
        $this->sendRequest();

        return $this->result;
    }
}