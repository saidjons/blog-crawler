<?php

namespace Saidjons\BlogCrawler\Tests;

use Orchestra\Testbench\TestCase;
use Saidjons\BlogCrawler\BlogCrawlerServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [BlogCrawlerServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
