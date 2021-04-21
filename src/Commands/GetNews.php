<?php

namespace Saidjons\BlogCrawler\Commands;

use Illuminate\Console\Command;
use Saidjons\BlogCrawler\CrawlerUserEnd;
use Saidjons\BlogCrawler\Jobs\ArticleCrawlerJob;

class GetNews extends Command
{
    
    protected $signature = 'saidjons:start {source}';

    protected  $confs=[

        'goal'  =>["home"],
        'daily'  =>[ "sport","epl","ucl","transfer","football"],
        'skysport'  => ["home","laliga","football","transfer"],
    ];

    protected $description = 'Get the latest news from a particular source';

   
    public function __construct()
    {
        parent::__construct();
    }
 
    public function handle()
    {
        

        foreach ($this->confs[$this->argument('source')] as $homepage) {
                
                
            $userEnd= new CrawlerUserEnd();
                $userEnd->bulk($this->argument('source'),$homepage);
                
                
                    
                  
            
                foreach ($userEnd->sourceWebsite->links as $link) {
                    if($link!=false){
                        
                        ArticleCrawlerJob::dispatch($this->argument('source'),$link);
                                      
                                            
                        }
                     

        }}



    }
}
