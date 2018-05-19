<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class CampaignUserPhoto extends BaseModel
{
    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'campaign_user_post_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'campaign_user_post_uuid';

    /**
     * @var array Attributes to disallow updating through an API update or put
     */
    public $immutableAttributes = ['created_at', 'deleted_at'];

    /**
     * @var array Relations to load implicitly by Restful controllers
     */
    public static $localWith = ['campaign', 'user', 'photo'];

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = ['campaign_user_post_id', 'campaign_id', 'user_id', 'photo_id', 'likes', 'comments'];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    public static function boot()
    {
        parent::boot();

        // Add functionality for updating a model
        static::saving(function (CampaignUserPhoto $campaignUserPhoto) {

            $interactionCost = $campaignUserPhoto->campaign->interaction_cost;

            if($campaignUserPhoto->original) {
                $likesDifference = $campaignUserPhoto->attributes['likes'] - $campaignUserPhoto->original['likes'];

                $commentsDifference = $campaignUserPhoto->attributes['comments'] - $campaignUserPhoto->original['comments'];
            } else {
                $likesDifference = $campaignUserPhoto->attributes['likes'];

                $commentsDifference = $campaignUserPhoto->attributes['comments'];
            }

            $moneyMade = $likesDifference * $interactionCost + $commentsDifference * $interactionCost;

            $campaignUserPhoto->user->balance = $campaignUserPhoto->user->balance + $moneyMade;
            $campaignUserPhoto->campaign->balance = $campaignUserPhoto->campaign->balance - $moneyMade;

            if($campaignUserPhoto->campaign->balance < 0) {

                $moneyBack = $campaignUserPhoto->campaign->balance * -1;
                $campaignUserPhoto->campaign->balance = $campaignUserPhoto->campaign->balance + $moneyBack;
                $campaignUserPhoto->campaign->status = 'finished';

                $campaignUserPhoto->user->balance = $campaignUserPhoto->user->balance - $moneyBack;
            }

            $campaignUserPhoto->campaign->save();
            $campaignUserPhoto->user->save();
        });
    }
    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [];
    }

    public function photo()
    {
        return $this->hasOne(Photo::class, 'photo_id', 'photo_id');
    }

    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'campaign_id', 'campaign_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

}
