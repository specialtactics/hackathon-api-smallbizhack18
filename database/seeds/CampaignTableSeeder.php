<?php

class CampaignTableSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {

    }

    /**
     * Run seeds to be ran only on production environments
     *
     * @return mixed
     */
    public function runProduction() {

    }

    /**
     * Run seeds to be ran on every environment (including production)
     *
     * @return mixed
     */
    public function runAlways() {

        $roles = \App\Models\Role::all();
        // Create an admin user
        $user = factory(App\Models\User::class)->create([
            'name'         => 'Small Business',
            'email'        => 'small@business.com',
            'primary_role' => $roles->where('name', \App\Models\Role::ROLE_BUSINESS)->first()->role_id,
        ]);

        $campaign = factory(App\Models\Campaign::class)->create([
            'name' => 'Small business hackathon',
            'description' => 'Get as many people involved with the hackathon',
            'location' => 'Surry Hills',
            'budget' => 500,
            'interaction_cost' => 1,
            'user_id' => $user->user_id,
        ]);

        factory(App\Models\CampaignTag::class)->create([
            'name' => 'hackathon',
            'campaign_id' => $campaign->campaign_id,
        ]);

    }
}
