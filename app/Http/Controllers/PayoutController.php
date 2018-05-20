<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\User;
use Specialtactics\L5Api\Http\Controllers\RestfulChildController;
use App\Services\RestfulService;
use DB;

class PayoutController extends RestfulChildController
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Payout::class;

    public static $parentModel = User::class;

    protected $paymentService = null;

    public function __construct(RestfulService $restfulService, PaymentService $paymentService)
    {
        parent::__construct($restfulService);

        $this->paymentService = $paymentService;
    }

    public function post($parentUuid, Request $request)
    {
        DB::beginTransaction();
        $resource =  parent::post($parentUuid, $request);
        DB::commit();

        $this->paymentService->payout($resource);

        return $this->response->item($resource, $this->getTransformer())->setStatusCode(201);
    }
}
