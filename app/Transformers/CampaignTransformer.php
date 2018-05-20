<?php

namespace App\Transformers;

use Specialtactics\L5Api\Transformers\RestfulTransformer;
use Specialtactics\L5Api\Models\RestfulModel;

class CampaignTransformer extends RestfulTransformer
{
    public function transform(RestfulModel $model)
    {
        $resource =  parent::transform($model);

        $resource['countLikes'] = $model->countLikesAttribute();
        $resource['countComments'] = $model->countCommentsAttribute();
        $resource['countPosts'] = $model->countPostsAttribute();
        return $resource;
    }

}