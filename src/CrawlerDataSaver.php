<?php
namespace Saidjons\BlogCrawler;

use Spatie\Url\Url;
 
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;
use Saidjons\BlogCrawler\Goaldotcom;
use Saidjons\BlogCrawler\CrawlerInterface;
use Saidjons\BlogCrawler\Models\CrawledPage;
use Saidjons\BlogCrawler\Exceptions\ImgNotFoundException;
use Saidjons\BlogCrawler\Exceptions\URLNotFoundException;
use Saidjons\BlogCrawler\Exceptions\BodyNotFoundException;
use Saidjons\BlogCrawler\Exceptions\TeaserNotFoundException;
use Saidjons\BlogCrawler\Exceptions\TitleNotFoundException;


class CrawlerDataSaver 
{
    public $website;

    protected $bulk_urls;
    protected $one_article=[];

    


    public function __construct(CrawlerInterface $website){
        $this->website=$website;
    }


    /**
     * this article crawles the 
     *
     * @param [type] $url
     * @return void
     */
    public function articleto_store($art,$model)        
    {
        $this->article_store($art,$model);

    }
    public function article_store($article,$model_id)
    {
        // $check_exists=CrawledPage::where('full_url',$article['full_url'])->where('tried',1)->get();
        // if (count($check_exists)>0) {
        //     # code...
        //     echo $article['full_url'];
        //     throw new \Exception("This url already Crawled . It exists");
        // }
        
        $model=CrawledPage::findOrFail($model_id);
        if (!isset($article['title'])) {
            throw new TitleNotFoundException("title not found".$article['full_url']);
        }
        $model->title=$article['title'];
        
        if (!isset($article['teaser'])) {
            
            throw new TeaserNotFoundException("Teaser not found ");
        }
        $model->teaser=$article['teaser'];
        if (!isset($article['body'])) {
            throw new BodyNotFoundException("Body not found");
        }
        $model->body=$article['body'];
         
       

        if(isset($article['img']))
        {
            $model->img=$article['img'];
        }
        $model->tried=1;
        if(!$model->save())
        {
            return "error model not saved ";
        }
        return "succes saved ".$model->id ." <br> title: ".$model->title;


    }
    public function article_crawler($url)
    {
        // $website=new $this->website_class;
        $art=$this->website->get_article($url);
        return $art;

    }
    
    public function loopover_links()    
    {
        
        foreach ($this->website->links as $link) {
            
            $article=$this->article_crawler($link);
            $result=$this->articleto_store($article);
            
            if (!$result) {
                return "failed article";
            }
            return "success saved article :".$result->id;
            
        }   
        if ($this->url_checker($this->website->links)) {
        }
    }

   
    public function get_pagesto_crawl()
    {
        if (!isset($this->website) && empty($this->website)) {
            return $this->website->pagesto_crawl;

        }
        return $this->website->pagesto_crawl;
    }

    /**
     * this bulk gets url and title of "homepage" like pages 
     *
     * @param [type] $urls
     * @return void
     */
    public function bulk_crawler(string $page)
    {

       if (isset($this->website->pagesto_crawl[$page])) {
           $result=$this->website->home($this->website->pagesto_crawl[$page]);
           if(!$result)
           {
               return "error not  crawled: ".$page;
           }
           return "succes";
           
       }

        

    }

    public function instantiate_article_after_bulk()
    {
        foreach ($this->website->links as  $link) {
            
           $data= $this->article_crawler($link);
           

        }
    }

    public function url_checker($data)      
    {
        if (!empty($data)) 
        {
            return true;
        }
        return false;

    }
    public function linksto_store()
    {
        if ($this->website->links) {
            // dd($this->website->links);
            foreach ($this->website->links as  $link) {
               $check= $this->link_store($link);
               if ($check===false) {
                   echo "not saved from linksto_store <b>already exists</b><br>";
                   continue;
               }
               echo "<b>saved<b> from linksto_store<br>";
                    

            }
        }
    }
    public function link_store($link)

    {
        $ch=CrawledPage::where('full_url',$link)->get();
        if (count($ch)) {
            return false;                     
        }else{


            
            $model=new CrawledPage();
            
            $model->full_url=$link;
            $domain=$this->website->get_domainof_link($link);
            $model->domain=$domain;
            $model->save();
            
            return true;
        }
    }

    public function crawl_links_from_db()
   {
       $uncrawleds=CrawledPage::where('domain',$this->website->domain)->where('tried',0)->get();

       if (count($uncrawleds)>=1) {
        
            foreach ($uncrawleds as  $one) {

                $this->website->get_article($one->full_url);
               


                // comment echo 
                try {
                     $this->article_store($this->website->article,$one->id);
                    //  $this->website->article=[];
                    //  $this->one_article=[];
                    
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
                

            }

       }
   }

   public function straight_single_store($article)
   {
        //  $check_exists=CrawledPage::where('full_url',$article['full_url'])->get();
        // if (count($check_exists)>0) {
        //     echo $article['full_url'];
        //     throw new \Exception("This url already Crawled . It exists");
        // }
        
        $model= new CrawledPage();
        if (!isset($article['title'])) {
            throw new TitleNotFoundException("title not found".$article['full_url']);
        }
        $model->title=$article['title'];
        
        if (!isset($article['teaser'])) {
            
            throw new TeaserNotFoundException("Teaser not found ");
        }
        $model->teaser=$article['teaser'];
        if (!isset($article['body'])) {
            throw new BodyNotFoundException("Body not found");
        }
        $model->body=$article['body'];
        $model->full_url=$article['full_url'];
        $model->domain=$article['domain'];
         
       

        if(isset($article['img']))
        {
            $model->img=$article['img'];
        }
        $model->tried=1;
        if(!$model->save())
        {
            return "error not saved";
        }
        return "succes saved ".$model->id ." <br> title: ".$model->title;


   }



}