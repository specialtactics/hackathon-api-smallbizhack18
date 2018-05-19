<?php

namespace App\Transformers;

use Specialtactics\L5Api\Transformers\RestfulTransformer;
use Specialtactics\L5Api\Models\RestfulModel;

class BaseTransformer extends RestfulTransformer
{
    public function transform(RestfulModel $model)
    {
        $resource =  parent::transform($model);

        $resource['id'] = $model->getUuidKey();

        return $resource;
    }

}