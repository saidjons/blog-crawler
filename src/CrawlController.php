<?php

namespace Saidjons\BlogCrawler;

use Spatie\Url\Url;
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;
use App\Http\Controllers\Controller;
use Saidjons\BlogCrawler\CrawlerUserEnd;

class CrawlController extends Controller
{

    public function ingnite_bulk($domain,$pagename)
    {


        $flagger=new CrawlerUserEnd();
        $response=$flagger->bulk($domain,$pagename);
        return $response;
    }

    public function single_link(Request $r)
    {


        $flagger=new CrawlerUserEnd();
        $response=$flagger->single($domain,$pagename);
        return $response;
    }

    public function try_linksfrom_db($domain)
    {
        $flagger=new CrawlerUserEnd();
        $response=$flagger->tryfromdb($domain);
        return $response;
    }
}
