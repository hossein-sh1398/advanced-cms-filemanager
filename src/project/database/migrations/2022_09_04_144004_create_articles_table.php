<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('lang')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('category_id');
            $table->boolean('special')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->longText('meta_description')->nullable();
            $table->integer('count')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('articles');
    }
};
