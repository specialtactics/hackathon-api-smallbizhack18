<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignUserPhoto extends Migration
{
    const TABLE_NAME = 'campaign_user_photos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->increments('campaign_user_post_id');
            $table->uuid('campaign_user_post_uuid')->unique();

            $table->unsignedInteger('campaign_id');
            $table->integer('user_id')->nullable();
            $table->bigInteger('photo_id');
            $table->integer('likes')->nullable();
            $table->integer('comments')->nullable();

            $table->unique(['campaign_id', 'user_id', 'photo_id']);
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('photo_id')->references('photo_id')->on('photos')->onDelete('set null');

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
