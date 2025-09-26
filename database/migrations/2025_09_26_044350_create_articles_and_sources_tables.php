<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesAndSourcesTables extends Migration
{
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. newsapi, guardian, nyt
            $table->string('name');
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable(); // id from provider if any
            $table->string('source_code'); // code referencing sources.code
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('url')->unique(); // dedupe on url
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->text('image')->nullable();
            $table->string('category')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('raw')->nullable(); // raw provider payload for debugging
            $table->timestamps();

            $table->index('published_at');
            $table->index('source_code');
        });

        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // link to users if available
            $table->json('sources')->nullable(); // ['nyt','guardian']
            $table->json('categories')->nullable(); // ['sports','tech']
            $table->json('authors')->nullable(); // ['Jane Doe']
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('sources');
    }
}
