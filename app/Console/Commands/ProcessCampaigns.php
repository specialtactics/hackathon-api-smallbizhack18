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
        $roles = \App\Models\Role::all();

        /** @var Campaign[] $campaigns */
        $campaigns = Campaign::where('status', 'active')->with('campaignTags')->get();
        
        //get all users
//        $user_provider_ids = User::query()->select('provider_id')->where('primary_role', '=',  Role::ROLE_SOCIALITE)->get();
        $user_provider_ids = User::select('provider_id')->where('primary_role', '=',  $roles->where('name', \App\Models\Role::ROLE_SOCIALITE)->first()->role_id)->get();
        dd($user_provider_ids);

        foreach ($campaigns as $campaign) {
            foreach($campaign->campaignTags as $tag) {
                dd($tag);
                $posts = $this->instagramService->fetchPostWithTag($tag);

//                dd($pos$$ts);
                foreach($posts as $post) {
                    if(in_array($post->id, $user_provider_ids)) {
                        //do something with it
                    }
                }
            }
        }


    }
}
