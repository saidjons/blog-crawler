<?php

namespace Saidjons\BlogCrawler;

use Saidjons\BlogCrawler\Models\CrawledPages;
use Spatie\Url\Url;
use Saidjons\BlogCrawler\Crawler;
use KubAT\PhpSimple\HtmlDomParser;

class DailyMailcouk implements CrawlerInterface
{

    // define('home_url','https://www.goal.com/en');
   // home page crawl data 
   public $domain='www.dailymail.co.uk';
   /**
    * $links strored filtered bulk articles links
    *
    * @var array
    */
   public $links=[];
   public $anchors=[];

   /**
    * variable to store data while crawling bulk articles before storing and filtering to
    * $Links 
    *
    * @var array
    */
   public $raw_links=[];

   // single article data 
   public $article=[];
   public $image;
   public $results;
   public $body;


   public $pagesto_crawl=[
       'home'      =>  'https://www.dailymail.co.uk/sport/index.html',
       'epl'    =>  'https://www.dailymail.co.uk/sport/premierleague/index.html',
       'sport'  =>  'https://www.dailymail.co.uk/sport/index.html',
       'ucl'    =>   'https://www.dailymail.co.uk/sport/champions_league/index.html',
       'transfer'  =>  'https://www.dailymail.co.uk/sport/transfernews/index.html',
       'football'  =>  'https://www.dailymail.co.uk/sport/football/index.html',





    ];

   public function __construct()
   {
        
   }
   

   public function get_article($url,$br=2)
   {

    $this->body='';
    $this->article=[];
           $dom = HtmlDomParser::file_get_html($url);
           
           $title=$dom->find('h2',0);
           $title=$title->plaintext;
           $teaser=$dom->find('ul.mol-bullets-with-font',0);
           $teaser=$teaser->plaintext;
           $image = $dom->find(' img',4);
           
           $bodys=$dom->find('div[itemprop=articleBody] p');
           $this->set_img_src($image->src);
           
           
           
           foreach ($bodys as  $bod) {
               if ($br > 0 && is_int($br)) {
                   
                   $this->body.=$bod->plaintext."<br>";
               }
               $this->body.=$bod->plaintext;
           }
           // $this->get_metas($url);
               
               

           
       
           $this->article=['body'=>$this->body,
                                'img'=>$this->image,
                                'title'=>$title,
                                'teaser'=>$teaser,
                            
                                'full_url'=>$url,
                                'domain'  => $this->get_domainof_link($url),
                            ];

        
         return $this->article;
   
   }

   public function get_domainof_link($link)
   {
       $url = Url::fromString($link);

           // return $url->getScheme()."://".$url->getHost();
           return $url->getHost();

   }

   public function set_img_src($src)
   {
       $this->image=$src;

       // if (!empty($src) && strlen($src)>10) {
       // }
         
   }



   public function home($url){
       $dom = HtmlDomParser::file_get_html($url);

      $articles=$dom->find('a');
       foreach ($articles as  $article) {
          
           $this->raw_links[]=['title'=>$article->plaintext,'href'=>$article->href];

       }

       $this->home_links_filter($this->raw_links);

       
   }
   public function remove_anchors($links)
   {
    
    

    
    foreach ($links as  $link) {
        $this->anchors[]=strstr($link, '#', true);
        
    }
    
        // unset($this->links);
        $this->links=$this->anchors;
      
   }
   public function unique_array($links)
   {
    //    $remove_achchors=$this->remove_anchors($links);
       $uniques = array_unique($links);


       // unset($this->links);
       $this->links=$uniques;
       // $unique->values()->all();


   }


   public function home_links_filter($raw_links)
   {
       foreach ($raw_links as  $link) {
           
           if (Url::fromString($link['href'])->getSegment(1)==='sport') {
               /**
                * here only getting football related links 
                * if you want other news too , delete below if statement , and return 
                * from "news if" 
                */
               if (Url::fromString($link['href'])->getSegment(2)==='football') {
                   $this->links[]=$this->make_full_url($link['href']);
                   # code...
               }

           }
       }
       $this->remove_anchors($this->links);
       $this->unique_array($this->links);
   }

   
   

   public function make_full_url($link)
   {
       if (strpos($link, "https") === 0) {
           //Starts with it
           return $link;
       }

       return $this->domain.substr($link,1);
       // return $link;

   }
   

  

      
      
}
