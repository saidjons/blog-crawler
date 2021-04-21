<?php


namespace Saidjons\BlogCrawler;

use Saidjons\BlogCrawler\Goaldotcom;
use Saidjons\BlogCrawler\DailyMailcouk;
use Saidjons\BlogCrawler\SkySportdotcom;
use Saidjons\BlogCrawler\CrawlerDataSaver;

class CrawlerUserEnd
{
     public $sourceWebsite;
    public $data_saver;
    public function __construct()
    {
        
    }

     public function bulk($domain,$pagename)
    {
        
          $this->setsourceWebsite($domain);
       
     

    // dd($this->sourceWebsite);

        $this->data_saver=new CrawlerDataSaver($this->sourceWebsite);
        if ($this->data_saver->website->pagesto_crawl[$pagename]) {
            # code...
            $this->data_saver->website->home($this->sourceWebsite->pagesto_crawl[$pagename]);

            // $this->data_saver->linksto_store();
             
    }



        // return response()->json([$domain,$pagename]);
    }


    /**
     * receiving request here ,  link query
     *
     * @param [request] $r
     * @return void
     */
     public function single($domain,$link)
    {
        
        $this->setsourceWebsite($domain);
        return $this->sourceWebsite->get_article($link);
    }


     public function tryfromdb($domain)
    {
        $this->setsourceWebsite($domain);
        $this->data_saver=new CrawlerDataSaver($this->sourceWebsite);
        $this->data_saver->crawl_links_from_db();
        
        // return response()->json($r->link);
    }

        public function setsourceWebsite($domain)
        {
                    if ($domain=='goal'){

                
                        $this->sourceWebsite=new Goaldotcom();

                        // #########working  bulk liks
                
                }elseif($domain=='daily'){

                    $this->sourceWebsite=new DailyMailcouk();

                }elseif($domain=='skysport'){

                    $this->sourceWebsite=new SkySportdotcom();
                }
        }



}