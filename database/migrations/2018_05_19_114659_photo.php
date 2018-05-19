<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Photo extends Migration
{
    const TABLE_NAME = 'photos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->increments('photo_id');
            $table->uuid('photo_uuid')->unique();

            $table->integer('user_id')->nullable();
            $table->bigInteger('post_id');
            $table->unsignedInteger('campaign_id');
            $table->string('caption')->nullable();
            $table->string('thumb')->nullable();
            $table->integer('views')->nullable();
            $table->string('url');
            $table->bigInteger('instagram_user_id')->nullable();
            $table->string('username')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('comments')->nullable();
            $table->string('location_id')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_slug')->nullable();
            $table->string('location_coordinate')->nullable();
            $table->string('tags')->nullable();
            $table->dateTime('created')->nullable();

            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');

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
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
