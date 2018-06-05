<?php

namespace App\Console\Commands;


use App\Crawler\LaGou;
use Illuminate\Console\Command;

class CapturePosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:capture-position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取就业职位信息';

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
        $output = $this->getOutput();
        $output->writeln("正在爬取职位信息...");

        $laGou = new LaGou();
        $position = $laGou->getPosition();

        echo $position;


    }
}
