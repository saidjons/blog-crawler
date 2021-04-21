<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawledPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->id();
            $table->string('full_url')->unique();
            $table->string('domain');
            $table->string('title')->nullable();
            $table->text('teaser')->nullable();
            $table->longText('body')->nullable();
            $table->string('img')->nullable();
            $table->boolean('tried')->nullable();
            $table->enum('accepted',['not yet','accepted','rejected'])->default('not yet');
            $table->text('transto_uz')->nullable();
            $table->mediumText('comment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawled_pages');
    }
}
