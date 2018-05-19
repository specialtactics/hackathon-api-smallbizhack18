<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\User;
use Specialtactics\L5Api\Http\Controllers\RestfulChildController;

class PayoutController extends RestfulChildController
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Payout::class;

    public static $parentModel = User::class;
}
