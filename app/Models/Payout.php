<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

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
    protected $fillable = ['amount', 'email', 'user_id'];

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
            'email' => 'required|email',
        ];
    }

    /**
     * @var array The attributes that should be cast to native types.
     */
    protected $casts = [
        'amount' => 'real',
    ];

    /**
     * Boot the model
     *
     * Add various functionality in the model lifecycle hooks
     */
    public static function boot()
    {
        parent::boot();

        // Add functionality for creating a model
        static::creating(function (Payout $model) {
            $user = User::findOrFail($model->user_id);

            // Check user's balance above 0
            if ($user->balance <= 0) {
                throw new AccessDeniedException('User does not have a balance above 0');
            }

            // Set amount at user's balance
            $model->amount = $user->balance;

            // Null the user's balance
            $user->balance = 0;
            $user->save();
        });
    }

    /**
     * Get batch ID for paypal - limited to 30 characters
     *
     * @return mixed|string
     */
    public function getBatchId() {
        $string = $this->getUuidKey();
        $string = str_replace('-', '', $string);
        $string = substr($string, 0, 30);
        return $string;
    }

}
