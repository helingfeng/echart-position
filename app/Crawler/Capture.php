<?php

namespace App\Crawler;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Capture
{
    protected $client = null;

    protected $positionUrl = 'https://www.lagou.com/jobs/positionAjax.json?px=new&needAddtionalResult=false';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getPositionListByPage($page = 1, $kd = '')
    {
        try {
            $options = [
                'form_params' => [
                    'pn' => $page,
                    'kd' => $kd,
                ],
                'headers' => [
                    'Referer' => 'https://www.lagou.com/jobs/list_'
                ]
            ];
            $res = $this->client->request('POST', $this->positionUrl, $options);
            $contents = $res->getBody();
            $contents = json_decode($contents, true);
            return $contents['content']['positionResult']['result'];

        } catch (GuzzleException $exception) {
            return array();
        }
    }
}
