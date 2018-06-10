<?php

namespace App\Console\Commands;


use App\Crawler\Capture;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PositionCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:csv-position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取就业职位信息，并存储为 Csv 文件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption('keyword', null, InputOption::VALUE_REQUIRED, '职位搜索关键词.');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $kd = $this->option('keyword');
        $output = $this->getOutput();
        $output->writeln("正在抓取关键字 {$kd} 相关职位数据...");

        $capture = new Capture();
        $positions = $capture->getPositionListByPage(1, $kd);
        $heading = array_keys(array_first($positions['data']));

        $filename = "positions_{$kd}.csv";
        $fp = fopen(storage_path('app/public') . "/{$filename}", 'w+');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($fp, $heading);
        $total_page = ceil($positions['total'] / 15);
        $output->writeln("关键字搜索共 {$total_page} 页，马上开始抓取...");
        for ($p = 1; $p <= $total_page; $p++) {
            sleep(1);
            $output->writeln("正在抓取第{$p}页.");
            $result = $capture->getPositionListByPage($p, $kd);
            $positions = $result['data'];
            foreach ($positions as $position) {
                foreach ($position as &$field) {
                    if (is_array($field)) {
                        $field = implode(',', $field);
                    } else if (!is_string($field)) {
                        $field = json_encode($field, JSON_UNESCAPED_UNICODE);
                    }
                }
                fputcsv($fp, $position);
            }
        }
        fclose($fp);
        $output->writeln("任务已完成，程序自动退出.");

    }
}
