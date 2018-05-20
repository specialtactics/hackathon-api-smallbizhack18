<?php

namespace App\Transformers;

use Specialtactics\L5Api\Transformers\RestfulTransformer;
use Specialtactics\L5Api\Models\RestfulModel;

class PhotoTransformer extends RestfulTransformer
{
    public function transform(RestfulModel $model)
    {
        $resource =  parent::transform($model);

        $resource['instagramUrl'] = $model->getInstagramUrlAttribute();
        $resource['postValue'] = $model->getPostValueAttribute();
        return $resource;
    }

}