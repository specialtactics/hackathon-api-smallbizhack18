<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignTagsTable extends Migration
{
    const TABLE_NAME = 'campaign_tags';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->increments('campaign_tag_id');
            $table->uuid('campaign_tag_uuid')->unique();

            $table->string('name');

            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
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
