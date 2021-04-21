<?php

namespace Saidjons\BlogCrawler;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Saidjons\BlogCrawler\Skeleton\SkeletonClass
 */
class BlogCrawlerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'blog-crawler';
    }
}
