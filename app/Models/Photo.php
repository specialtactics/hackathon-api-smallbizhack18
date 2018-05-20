<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use App\Transformers\PhotoTransformer;

class Photo extends BaseModel
{

    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'photo_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'photo_uuid';

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
    public static $transformer = PhotoTransformer::class;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = ['campaign_id', 'post_id', 'code', 'instagram_user_id', 'url', 'caption', 'user_id', 'username', 'likes', 'comments', 'location_id', 'location_name', 'location_slug', 'location_coordinate', 'tags', 'created'];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];


    public static function boot()
    {
        parent::boot();

        // Add functionality for updating a model
        static::saved(function (Photo $photo) {

            $campaignUserPhotoAttributes = [
                'user_id' => $photo->user_id,
                'campaign_id' => $photo->campaign_id,
                'photo_id' => $photo->photo_id,
            ];
            $campaignUserPhoto = $campaignUserPhotoAttributes + [
                'likes' => $photo->likes,
                'comments' => $photo->comments
            ];

            CampaignUserPhoto::updateOrCreate($campaignUserPhotoAttributes, $campaignUserPhoto);
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

    public function getInstagramUrlAttribute()
    {
        return 'https://www.instagram.com/p/' . $this->code;
    }
    public function getPostValueAttribute()
    {
        return ($this->likes + $this->comments) * $this->campaign->interaction_cost;
    }

    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'campaign_id', 'campaign_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function campaignUserPhoto()
    {
        return $this->hasOne(Photo::class, 'photo_id', 'photo_id');
    }
}
