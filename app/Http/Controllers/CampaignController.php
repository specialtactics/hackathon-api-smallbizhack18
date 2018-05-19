<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Services\InstagramService;
use App\Services\RestfulService;

class CampaignController extends BaseController
{
    protected $instagramService;

    public function __construct(RestfulService $restfulService, InstagramService $instagramService)
    {
        parent::__construct($restfulService);
        $this->instagramService = $instagramService;
    }
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Campaign::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;


    public function processAll()
    {
        $this->instagramService->processCampaigns();
        return $this->response->noContent()->setStatusCode(200);

    }
}
