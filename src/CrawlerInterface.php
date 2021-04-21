<?php

namespace Saidjons\BlogCrawler;

interface CrawlerInterface
{
    
      public function get_article($url);

    

    public function home($url);

}