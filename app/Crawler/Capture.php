<?php

namespace App\Crawler;


use Faker\Provider\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Capture
{
    protected $client = null;

    protected $positionUrl = 'https://www.lagou.com/jobs/positionAjax.json?px=new&needAddtionalResult=false';

    public function __construct()
    {
        $this->client = new Client(['cookies' => false]);
    }

    public function getPositionListByPage($page = 1, $kd = '')
    {
        try {
//            $cookies = "JSESSIONID=" + get_uuid() + ";" \"user_trace_token=" + get_uuid() + "; LGUID=" + get_uuid() + "; index_location_city=%E6%88%90%E9%83%BD; " \"SEARCH_ID=" + get_uuid() + '; _gid=GA1.2.717841549.1514043316; ' \'_ga=GA1.2.952298646.1514043316; ' \'LGSID=' + get_uuid() + "; " \"LGRID=" + get_uuid() + "; "

            $options = [
                'form_params' => [
                    'pn' => $page,
                    'kd' => $kd,
                    'first' => $page == 1 ? 'true' : 'false'
                ],
                'headers' => [
                    'Referer' => 'https://www.lagou.com/jobs/list_',
                    'Host' => 'www.lagou.com',
                    'Origin' => 'https://www.lagou.com',
                    'Accept' => 'application/json, text/javascript, */*; q=0.01',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Accept-Language' => 'zh-CN,zh;q=0.9',
                    'Connection' => 'keep-alive',
                    'X-Anit-Forge-Code' => '0',
                    'X-Anit-Forge-Token' => 'hNone',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
                    'Cookie' => "_ga=GA1.2.358577271.1527765480; user_trace_token=" . Uuid::randomLetter()
                        . "; LGUID=" . Uuid::randomLetter()
                        . "; index_location_city=%E5%85%A8%E5%9B%BD; JSESSIONID=" . Uuid::randomLetter()
                        . "; Hm_lvt_4233e74dff0ae5bd0a3d81c6ccf756e6=1527765481,1528206374; _gid=GA1.2.1801407859.1528510840; TG-TRACK-CODE=search_code; LGSID=" . Uuid::randomLetter()
                        . "; PRE_UTM=; PRE_HOST=; SEARCH_ID=" . Uuid::randomLetter()
                        . "; _gat=1; LGRID=" . Uuid::randomLetter()
                        . "; Hm_lpvt_4233e74dff0ae5bd0a3d81c6ccf756e6=1528552371",
                    'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8",
                ]
            ];
            $res = $this->client->request('POST', $this->positionUrl, $options);
            $contents = $res->getBody();
            $contents = json_decode($contents, true);

            if ($res->getStatusCode() == 200) {
                if ($contents['success']) {
                    return $contents['content']['positionResult']['result'];
                } else {
                    echo $contents['msg'] . PHP_EOL;
                    exit();
                }
            } else {
                echo '网络异常，请检查.' . PHP_EOL;
                exit();
            }
        } catch (GuzzleException $exception) {
            echo $exception->getFile() . $exception->getMessage();
            exit();
        }
    }
}
