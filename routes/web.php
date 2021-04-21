<?php

use Illuminate\Support\Facades\Route;
use saidjon\BlogCrawler\CrawlerUserEnd;
use saidjons\BlogCrawler\CrawlController;
use saidjons\BlogCrawler\Jobs\ArticleCrawlerJob;


 
 
Route::get('crawl/{source}',function($source){
    $confs=[

                'goal'  =>["home"],
                'daily'  =>[ "sport","epl","ucl","transfer","football"],
                'skysport'  => ["home","laliga","football","transfer"],
            ];
            
           
            foreach ($confs[$source] as $homepage) {
                
                
                $userEnd= new CrawlerUserEnd();
                    $userEnd->bulk($source,$homepage);
                    
                    
                        
                      
                
                    foreach ($userEnd->sourceWebsite->links as $link) {
                        if($link!=false){
                            
                            ArticleCrawlerJob::dispatch($source,$link);
                
                                // if (!CrawledPage::where('full_url',$link)) {
                                //     # code...
                                // }
                                echo $source." -  ".$link;
                                
                                echo "<br>";
                            
                            }
                         

            }}

});