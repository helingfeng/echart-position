<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class TestGoutte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET','https://www.lagou.com/gongsi/2-0-0.json',['headers'=>['Referer'=>' https://www.lagou.comclear/gongsi/0-0-0']]);

        $this->output->writeln($response->getBody()->getContents());
    }
}
