<?php

namespace Saidjons\BlogCrawler\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Saidjons\BlogCrawler\Models\CrawledPage;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UrlHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $link;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link=$link;
        $this->queue='url';
        $this->onQueue("high");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ArticleCrawlerJob::dispatch('daily',$this->link);
        
        if (!CrawledPage::where('full_url',$this->link)) {
            # code...
        }
    }
}
