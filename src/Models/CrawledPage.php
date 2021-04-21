<?php

namespace Saidjons\BlogCrawler\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawledPage extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $fillable = [
        'full_url','domain',
        'title','teaser','body',
            'img','tried'                  
      ];
}