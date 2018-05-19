<?php

namespace App\Transformers;

use Specialtactics\L5Api\Transformers\RestfulTransformer;

class BaseTransformer extends RestfulTransformer
{
    public function transform(RestfulModel $model)
    {
        $resource =  parent::transform($model);

        $resource['id'] = $model->getUuidKey();

        return $resource;
    }

}