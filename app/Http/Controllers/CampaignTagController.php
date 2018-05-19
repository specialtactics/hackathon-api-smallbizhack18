<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Specialtactics\L5Api\Http\Controllers\RestfulChildController;

use App\Models\Campaign;
use App\Models\CampaignTag;

class CampaignTagController extends RestfulChildController
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = CampaignTag::class;

    /**
     * @var BaseModel The parent model associated with this controller
     */
    public static $parentModel = Campaign::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;
}
