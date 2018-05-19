<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignsTable extends Migration
{
    const TABLE_NAME = 'campaigns';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->increments('campaign_id');
            $table->uuid('campaign_uuid')->unique();

            $table->enum('status', ['active', 'finished', 'cancelled'])->default('active');

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('location');

            $table->decimal('budget', 8, 2);
            $table->decimal('interaction_cost', 8, 2);


            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');

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
