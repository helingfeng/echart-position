<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;
use VDB\Spider\Discoverer\DiscovererSet;
use VDB\Spider\Discoverer\XPathExpressionDiscoverer;
use VDB\Spider\Event\SpiderEvents;
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

        $spider->getDiscovererSet()->maxDepth = 1;
        $spider->getQueueManager()->maxQueueSize = 100;

        $statsHandler = new StatsHandler();
        $spider->getDispatcher()->addSubscriber($statsHandler);


        $output->writeln("爬虫运行中...");

        $spider->getDispatcher()->addListener(
            SpiderEvents::SPIDER_CRAWL_RESOURCE_PERSISTED,
            function (GenericEvent $event) use ($output) {
                $output->writeln($event->getArguments());
            }
        );

        $spider->getDispatcher()->addListener(
            SpiderEvents::SPIDER_CRAWL_PRE_REQUEST,
            function (GenericEvent $event) use ($output) {
                $output->writeln($event->getArguments());
            }
        );

        $spider->getDispatcher()->addListener(
            SpiderEvents::SPIDER_CRAWL_ERROR_REQUEST,
            function (GenericEvent $event) use ($output) {
                $output->writeln($event->getArguments());
            }
        );

        $spider->crawl();

        $output->writeln("");
        $output->writeln("略过:" . count($statsHandler->getFiltered()));
        $output->writeln("失败:" . count($statsHandler->getFailed()));
        $output->writeln("成功:" . count($statsHandler->getPersisted()));

        $output->writeln("");
        $output->writeln("获取资源: ");
        foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {
            $output->writeln(" - " . $resource->getCrawler()->filterXpath('//title')->text());
//            DB::table('pt_origin_html')->insert([
//                'title' => $resource->getCrawler()->filterXpath('//title')->text(),
//                'url' => $resource->getUri(),
//                'html' => $resource->getResponse()->getBody(),
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ]);
        }
    }
}
