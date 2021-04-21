<?php
namespace Saidjons\BlogCrawler;

use Spatie\Url\Url;
use KubAT\PhpSimple\HtmlDomParser;

class SkySportdotcom implements CrawlerInterface
{
    // define('home_url','https://www.goal.com/en');
     // home page crawl data 
     protected $home_url="https://www.skysports.com/";
     public $domain='www.skysports.com';
     // public $home_paginate_url='https://www.goal.com/en/news/2';
     public $home_paginate_url='https://www.goal.com/en/news/';
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
     public $article;
     public $image;
     public $results;
     public $body;
 
 
     public $pagesto_crawl=[
         'home'      =>  'https://www.skysports.com',
         'laliga'    =>  'https://www.skysports.com/la-liga',
         'football'  =>  'https://www.skysports.com/football',
         'transfer' =>    'https://www.skysports.com/football/transfer-news',
 
 
 
 
 
      ];
     // public $home_url='https://www.skysports.com';
 
     // public $home_url="https://en.as.com/";
 
     public function __construct()
     {
          
 
     }
 
     public function get_article($url,$br=2)
     {
 
             $dom = HtmlDomParser::file_get_html($url);
             
             $title=$dom->find('h1',0);
             $title=$title->plaintext;
             $teaser=$dom->find('p',1);
             $teaser=$teaser->plaintext;
             
             $bodys=$dom->find('div.sdc-article-body > p');
             $image = $dom->find('div img.sdc-article-image__item',0);
             $image=$image->src;
            //  $this->set_img_src($image->src);
             
              
             
             foreach ($bodys as  $bod) {
                 if ($br > 0 && is_int($br)) {
                    
                     $this->body.=$bod->plaintext.append_br($br);
                 }
                 $this->body.=$bod->plaintext;
             }
             // $this->get_metas($url);
                 
            return  $this->article=['body'=>$this->body,
                                'img'=>$image,
                                'title'=>$title,
                                'teaser'=>$teaser,
                                'full_url'=>$url,
                                'domain'  => $this->get_domainof_link($url),

                                ];
 
             
         
            //  return $results= ['body'=>$this->body,'img'=>$this->image,'title'=>$title,'teaser'=>$teaser];
     
     }

     public function get_domainof_link($link)
     {
         $url = Url::fromString($link);
 
             // return $url->getScheme()."://".$url->getHost();
             return $url->getHost();
 
     }

     /**
      * not implemented here . returned img has only one src
      *
      * @param [type] $src
      * @return void
      */
     public function set_img_src($src)
     {
         $this->image=$src;
 
         if (!empty($src) && strlen($src)>10) {
         }
           
     }
 
 
 
   
     
 
     
     public function home($url){
         $dom = HtmlDomParser::file_get_html($url);
 
        $articles=$dom->find('a');
         foreach ($articles as  $article) {
            
             $this->raw_links[]=['title'=>$article->plaintext,'href'=>$article->href];
 
         }
 
         $this->home_links_filter($this->raw_links);
 
         
     }
 
     public function home_links_filter($raw_links)
     {
         foreach ($raw_links as  $link) {
             
             if (Url::fromString($link['href'])->getSegment(2)==='news') {
                 /**
                  * here only getting football related links 
                  * if you want other news too , delete below if statement , and return 
                  * from "news if" 
                  */
                 if (Url::fromString($link['href'])->getSegment(1)==='football' ||Url::fromString($link['href'])->getSegment(1)==='transfer') {
                     # code...
                     $this->links[]=$this->make_full_url($link['href']);
                 }
 
             }
         }
    //      $this->remove_anchors($this->links);
       $this->unique_array($this->links);

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

 
 
     
 
    //  public function make_full_url($link)
    //  {
         
    //      // return $this->home_url.substr($link,1);
    //      return $link;
 
    //  }

     public function make_full_url($link)
     {
         if (strpos($link, "https") === 0) {
             //Starts with it
             return $link;
         }
  
         return $this->home_url.substr($link,1);
         // return $link;
  
     }
 
 
 }