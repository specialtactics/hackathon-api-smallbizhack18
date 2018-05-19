<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Photo;
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
     * ProcessCampaigns constructor.
     * @param InstagramService $instagramService
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
        $users = User::select()
            ->where('primary_role', '=',  $roles->where('name', \App\Models\Role::ROLE_SOCIALITE)->first()->role_id)
            ->whereNotNull('provider_id')
            ->get();
        $user_provider_ids = $users->pluck('provider_id');

        foreach ($campaigns as $campaign) {
            foreach($campaign->campaignTags as $tag) {
                $posts = $this->instagramService->fetchPostWithTag($tag->name);


                foreach($posts as $post) {
                    if($post->getUser() && in_array( $post->getUser()->getId(), $user_provider_ids->toArray())) {

                        $newPost = [
                            'campaign_id' => $campaign->campaign_id,
                            'post_id' => $post->getId(),
                            'url' => $post->getUrl(),
                            'thumb' => get_class($post) == 'Smochin\Instagram\Model\Photo' ? '' : $post->getThumb(),
                            'views' => get_class($post) == 'Smochin\Instagram\Model\Photo' ? '' : $post->getViews(),
                            'caption' => $post->getCaption(),
                            'user_id' => $post->getUser()->getId(),
                            'username' => $post->getUser()->getUserName(),
                            'likes' => $post->getLikesCount(),
                            'comments' => $post->getCommentsCount(),
                            'location_id' => $post->getLocation() ? $post->getLocation()->getId() : null,
                            'location_name' => $post->getLocation() ? $post->getLocation()->getName() : null,
                            'location_slug' => $post->getLocation() ? $post->getLocation()->getSlug() : null,
                            'location_coordinate' => $post->getLocation() ? $post->getLocation()->getCoordinate : null,
                            'tags' => \GuzzleHttp\json_encode($post->getTags()),
                            'created' => $post->getCreated()
                        ];

                        //do something with it
                        Photo::updateOrCreate(['post_id' => $post->getId()], $newPost);
                    }
                }
            }
        }


    }
}
