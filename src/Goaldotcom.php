<?php

namespace Saidjons\BlogCrawler;

use Saidjons\BlogCrawler\Models\CrawledPages;
use Spatie\Url\Url;
use Saidjons\BlogCrawler\Crawler;
use KubAT\PhpSimple\HtmlDomParser;

class Goaldotcom implements CrawlerInterface

{
     
    // home page crawl data 
    protected $home_url="https://www.goal.com/en";
    public $domain='www.goal.com';
    public $home_paginate_url='https://www.goal.com/en/news/';


    public $pagesto_crawl=[
        
        // public $home_paginate_url='https://www.goal.com/en/news/2';
        'paginate' =>  'https://www.goal.com/en/news/',
        "home"     =>  'https://www.goal.com/en',
        "transfer"  => 'https://www.goal.com/en/transfer-news',


     ];


    public $links=[];
    public $raw_links=[];

    // single article data 
    public $article;
    public $image;
    public $results;
    public $body;


    public function __construct()
    {
    //  $this->article=$this->get_article($url);
        // print_r($this->links);
        // $this->data_collect();
    }

    public function data_collect(){

        
    }

    public function links_checker(){
        if(count($this->links)>0){
            return true;
        }else{
            return false;
        }
    }


    // public function start_curl($url){
    //     $ch=curl_init();
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    //     curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    //     curl_setopt($ch,CURLOPT_URL,$url);
    //     $results=curl_exec($ch);
    //     curl_close($ch);
        
    //     // print_r($results);
    //     return $results;
    // }

    public function get_domainof_link($link)
    {
        $url = Url::fromString($link);

            // return $url->getScheme()."://".$url->getHost();
            return $url->getHost();

    }


    public function get_article($url,$br=2)
    {

        $this->article=[];
        $this->body='';
            $dom = HtmlDomParser::file_get_html($url);
            
      
            $title=$dom->find('h1[class=article-headline] ',0);
            if($title){
            
            $title=$title->plaintext;
            }
            if (!isset($title)) {
                
                  $title=$dom->find('.article-header',1);
          }  

          
            
            //  $teaser=$dom->find('div.teaser',1);
            $teaser=$dom->find('div[class=teaser]',0);
            $teaser=$teaser->plaintext;

            $bodys=$dom->find('div.body p');
            $image = $dom->find('div img',2);
            $this->set_img_src($image->src);


            foreach ($bodys as  $bod) {

                if ($br > 0 && is_int($br)) {
                   
                    $this->body.=$bod->plaintext.append_br($br);
                }
                
                $this->body.=$bod->plaintext.append_br($br);
            }
            // $this->get_metas($url);
                
            /**
             * 
             * this needs refactorting ,  no need for local property
             * this->article
             */

           return  $this->article=['body'=>$this->body,
                                'img'=>$this->image?? "null",
                                'title'=>$title??'null',
                                'teaser'=>$teaser,
                                'full_url'=>$url,
                                'domain'  => $this->get_domainof_link($url),

                                ];

    
    }


        public function set_img_width($src,$width=null):string
        {
            if (isset($width)) {
                
                $img_sub=substr($src,0,-5);
                $img_sub.='w='.$width;
                return $img_sub;
            }

            $img_sub=substr($src,0,-5);
                $img_sub.='w=1000';
                return $img_sub;

        }
        public function set_img_src($src)
        {
            if (!empty($src) && strlen($src)>10) {
                $this->image=$this->set_img_width($src,1000);
            }
              
        }

  
        public function data_echo(){

            }
        
        public function get_metas($url):array{

            $metas=get_meta_tags($url);
            
            // foreach ($metas as $key => $value) {
                
            //     echo $key." is key and the value is :".$value;
            //     echo "<br>";
            // }
            return $metas;
    }


    public function home($url){
        $dom = HtmlDomParser::file_get_html($url);

       $articles=$dom->find('a');
        foreach ($articles as  $article) {
           
            $this->raw_links[]=['title'=>$article->plaintext,'href'=>$article->href];
            $this->home_links_filter();

        }

        
    }
    /**
     * return unique links and store it in $this->links
     */

    public function unique_array($links)
    {
        $uniques = array_unique($links);


        // unset($this->links);
        $this->links=$uniques;
        // $unique->values()->all();


    }
    public function bettingUrl($link)
    {
        $pos=strpos($link,'betting');
        if ($pos) {
            return true;
        }
        return false;
    }
    public function home_links_filter()
    {
        // unset($this->links);
        foreach ($this->raw_links as  $link) {
            
            if (Url::fromString($link['href'])->getSegment(2)==='news' && strlen($link['href'])>20 && !$this->bettingUrl($link['href'])) {
                

                $this->links[]=$this->make_full_url($link['href']);
                
            }
        }
        $this->unique_array($this->links);
    }
 
    
    public function make_full_url($link)
    {
        
        return $this->home_url.substr($link,3);
    }

   

}
