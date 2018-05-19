<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class CampaignTag extends BaseModel
{
    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'campaign_tag_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'campaign_tag_uuid';

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
    protected $fillable = [
        'name', 'campaign_id',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [
        'campaign_id',
    ];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'name' => 'required|string'
        ];
    }

}
