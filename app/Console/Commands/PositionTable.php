<?php

namespace App\Console\Commands;


use App\Crawler\Capture;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class PositionTable extends Command
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
    protected $description = '演示抓取就业职位信息，并在控制台输出第一页';

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

        $capture = new Capture();
        $positions = $capture->getPositionListByPage();

        $row = [];
        $heading = [
            '职位名称',
            '月薪',
            '职位优势',
            '城市',
            '公司',
        ];
        foreach ($positions as $position) {
            array_push($row,[
                $position['positionName'],
                $position['salary'],
                $position['positionAdvantage'],
                $position['city'],
                $position['companyFullName'],
            ]);
        }
        $table = new Table($output);
        $table->setHeaders($heading)->setRows($row);
        $table->render();
    }
}
