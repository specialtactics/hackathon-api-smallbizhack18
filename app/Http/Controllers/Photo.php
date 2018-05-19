<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Photo extends BaseController
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = BaseModel::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    
}
