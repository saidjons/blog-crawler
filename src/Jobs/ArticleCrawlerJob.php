<?php

namespace Saidjons\BlogCrawler\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\SerializesModels;
use Saidjons\BlogCrawler\CrawlerUserEnd;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Saidjons\BlogCrawler\Models\CrawledPage;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ArticleCrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $link;
        public $domain;
        public  $article;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain,$link)
    {
        $this->link=$link;
        $this->domain=$domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        

        if(count(CrawledPage::where('full_url',$this->link)->get())==0){

            $userEnd=new CrawlerUserEnd();
            $this->article=$userEnd->single($this->domain,$this->link);
            sleep(4);
    
        
           $model=new  CrawledPage();
           $model->title=$this->article['title'];
           $model->teaser=$this->article['teaser'];
           $model->body=$this->article['body'];
           $model->full_url=$this->article['full_url'];
           $model->img=$this->article['img'];
           $model->domain=$this->article['domain'];
           $model->save();

           
        }else{

             
        } 

        // if (!$res) { 
        //     # code...
        //     Log::info('Making new directory');
          

        // if (!CrawledPage::where('full_url',$this->link)->get()) {
        //     # code...
        //     }
        // }
         
        
    }
}
