<?php

namespace App\Crawler;


use GuzzleHttp\Client;

class LaGou
{
    protected $client = null;

    protected $positionUrl = 'https://www.lagou.com/jobs/positionAjax.json?px=new&needAddtionalResult=false';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getPosition()
    {
        $headers = ['Referer' => 'https://www.lagou.com/jobs/list_'];
        $response = $this->client->request('GET', $this->positionUrl, array('headers' => $headers));
        return $response->getBody()->getContents();
    }
}
