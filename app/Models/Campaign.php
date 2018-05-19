<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class Campaign extends BaseModel
{
    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'campaign_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'campaign_uuid';

    /**
     * @var array Attributes to disallow updating through an API update or put
     */
    public $immutableAttributes = ['created_at', 'deleted_at'];

    /**
     * @var array Relations to load implicitly by Restful controllers
     */
    public static $localWith = ['campaignTags'];

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'location', 'budget', 'interaction_cost', 'user_id'];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * @var array The attributes that should be cast to native types.
     */
    protected $casts = [
        'budget' => 'real',
        'interaction_cost' => 'real',
    ];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'name' => 'required|string',
            'location' => 'required|string',
            'budget' => 'required|numeric',
            'interaction_cost' => 'required|numeric',
            'user_id' => 'integer',
        ];
    }

    public function campaignTags() {
        return $this->hasMany(CampaignTag::class, 'campaign_id', 'campaign_id');
    }

}
