<?php

namespace App\Services;
use App\Models\Campaign;
use App\Models\Photo;
use App\Models\User;
use Smochin\Instagram\Crawler;


/**
 * Class InstagramService
 *
 * @package App\Services
 */
class InstagramService
{

    public function fetchPostWithTag($tag = 'smallBizHack')
    {
        $crawler = new Crawler();
        $media = $crawler->getMediaByTag($tag);
        return $media;
    }

    public function processCampaigns()
    {
        $roles = \App\Models\Role::all();

        /** @var Campaign[] $campaigns */
        $campaigns = Campaign::where('status', 'active')->with('campaignTags')->get();

        //get all users
        $users = User::select()
            ->where('primary_role', '=',  $roles->where('name', \App\Models\Role::ROLE_SOCIALITE)->first()->role_id)
            ->whereNotNull('provider_id')
            ->get();

        foreach ($campaigns as $campaign) {
            foreach($campaign->campaignTags as $tag) {
                $posts = $this->fetchPostWithTag($tag->name);

                foreach($posts as $post) {
                    if($post->getUser()) {
                        $user = $users->firstWhere('provider_id', '=', $post->getUser()->getId());

                        if($user) {
                            $newPost = [
                                'campaign_id' => $campaign->campaign_id,
                                'post_id' => $post->getId(),
                                'user_id' => $user->user_id,
                                'code' => $post->getCode(),
                                'url' => $post->getUrl(),
                                'thumb' => get_class($post) == 'Smochin\Instagram\Model\Photo' ? '' : $post->getThumb(),
                                'views' => get_class($post) == 'Smochin\Instagram\Model\Photo' ? '' : $post->getViews(),
                                'caption' => $post->getCaption(),
                                'instagram_user_id' => $post->getUser()->getId(),
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
}