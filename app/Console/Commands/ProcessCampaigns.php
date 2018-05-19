<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Role;
use App\Models\User;
use App\Services\InstagramService;
use Illuminate\Console\Command;

class ProcessCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process campaigns, getting photos from instagram';

    protected $instagramService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Campaign[] $campaigns */
        $campaigns = Campaign::where('active', true)->with('tags')->first();
        
        //get all users
        $user_provider_ids = User::select('provider_id')->where('primary_role', Role::ROLE_SOCIALITE)->get();

        foreach ($campaigns as $campaign) {
            foreach($campaign->campaignTags as $tag) {
                $posts = $this->instagramService->fetchPostWithTag($tag);

                dd($posts);
                foreach($posts as $post) {
                    if(in_array($post->id, $user_provider_ids)) {
                        //do something with it
                    }
                }
            }
        }


    }
}
