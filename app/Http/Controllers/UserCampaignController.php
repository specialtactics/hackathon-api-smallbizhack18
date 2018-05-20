<?php

namespace App\Http\Controllers;

use App\Models\CampaignUserPhoto;
use App\Models\Photo;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Specialtactics\L5Api\Http\Controllers\RestfulChildController;
use App\Models\Campaign;
use App\Models\User;
use DB;

class UserCampaignController extends RestfulChildController
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Campaign::class;

    /**
     * @var BaseModel The parent model associated with this controller
     */
    public static $parentModel = User::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    /**
     * @param $parentUuid
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function post($parentUuid, Request $request)
    {
        DB::beginTransaction();

        // Create campaign
        $model = new static::$model;
        $resource =  parent::post($parentUuid, $request);

        // Add tags
        $tags = $request->request->get('tags');

        // Create a tag entity for every tag added
        if (is_array($tags) && !empty($tags)) {
            foreach ($tags as $tag) {
                $tagData = ['name' => $tag, 'campaign_id' => $resource->getKey()];

                try {
                    $this->api->post('campaigns/' . $resource->getUuidKey() . '/tags', $tagData);
                } catch (\Dingo\Api\Exception\InternalHttpException $e) {
                    DB::rollBack();
                    return $this->prependResponseMessage($e->getResponse(), 'Error creating tag; ');
                }
            }
        }

        DB::commit();

        // Retrieve full model
        $resource = $model::with($model::$localWith)->where($model->getKeyName(), '=', $resource->getKey())->first();

        return $this->response->item($resource, $this->getTransformer())->setStatusCode(201);
    }

    public function getAll($uuid, Request $request)
    {
        $user = User::findOrFail($uuid);

        if($user->primaryRole->name == Role::ROLE_SOCIALITE) {
            $campaigns = Campaign::with('photos', 'campaignTags')->get();
            $photos = Photo::where('user_id', '=', $user->user_id)->get();

            $uniqueCampaigns = $campaigns->whereIn('campaign_id', array_pluck($photos->toArray(), 'campaign_id'));

            foreach ($uniqueCampaigns as $campaign) {
                /** @var Campaign $campaign */
                $photoCollection = $campaign->photos->filter(function ($value, $key) use ($user) {
                    return $value['user_id'] == $user->user_id;
                });

                $campaign->setRelation('photos', $photoCollection);
            }

            return $this->response->collection($uniqueCampaigns, $this->getTransformer())->setStatusCode(200);

        } else {

            return parent::getAll($uuid, $request); // TODO: Change the autogenerated stub
        }
    }
}
