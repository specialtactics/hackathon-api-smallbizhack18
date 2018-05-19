<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class Payout extends BaseModel
{
    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'payout_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'payout_uuid';

    /**
     * @var array Attributes to disallow updating through an API update or put
     */
    public $immutableAttributes = ['created_at', 'deleted_at'];

    /**
     * @var array Relations to load implicitly by Restful controllers
     */
    public static $localWith = [];

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = ['amount', 'user_id'];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = ['user_id'];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'amount' => 'required|numeric',
        ];
    }

    /**
     * @var array The attributes that should be cast to native types.
     */
    protected $casts = [
        'amount' => 'real',
    ];

}
