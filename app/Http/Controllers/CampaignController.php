<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Specialtactics\L5Api\Http\Controllers\RestfulChildController;
use App\Models\Campaign;
use App\Models\User;
use DB;

class CampaignController extends RestfulChildController
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
}
