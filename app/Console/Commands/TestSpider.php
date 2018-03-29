<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use VDB\Spider\Discoverer\XPathExpressionDiscoverer;
use VDB\Spider\Spider;
use VDB\Spider\StatsHandler;

class TestSpider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test-spider';

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
        $output = $this->getOutput();
        //
        $spider = new Spider('https://www.liepin.com/company/');
        $spider->getDiscovererSet()->set(new XPathExpressionDiscoverer("//div[@class='company-place']//a"));
        $spider->getDiscovererSet()->maxDepth = 2;
//        $spider->getQueueManager()->maxQueueSize = 10;

        $statsHandler = new StatsHandler();
        $spider->getQueueManager()->getDispatcher()->addSubscriber($statsHandler);
        $spider->getDispatcher()->addSubscriber($statsHandler);

        $spider->crawl();

        $output->writeln("队列:" . count($statsHandler->getQueued()));
        $output->writeln("略过:" . count($statsHandler->getFiltered()));
        $output->writeln("失败:" . count($statsHandler->getFailed()));
        $output->writeln("成功:" . count($statsHandler->getPersisted()));

        $output->writeln("");
        $output->writeln("获取资源: ");
        foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {
            $output->writeln(" - " . $resource->getCrawler()->filterXpath('//title')->text());
        }

    }
}
