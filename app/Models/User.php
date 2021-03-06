<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Hash;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'user_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'user_uuid';

    /**
     * @var array Relations to load implicitly by Restful controllers
     */
    public static $localWith = ['primaryRole', 'roles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'primary_role', 'provider', 'provider_id', 'avatar', 'nickname', 'access_token', 'followed_by', 'follows', 'media'
    ];

    /**
     * The attributes that should be hidden for arrays and API output
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'primary_role',
    ];

    /**
     * Model's boot function
     */
    public static function boot()
    {
        parent::boot();

        // Has user password, if not already hashed
        static::saving(function (User $user) {
            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules() {
        return [
            'email' => 'required|email',
            'name'  => 'required|min:3',
            'password' => 'required',
        ];
    }

    /**
     * User's primary role
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function primaryRole() {
        return $this->belongsTo(Role::class, 'primary_role');
    }

    /**
     * User's secondary roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * User's campaigns
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function campaigns() {
        return $this->hasMany(Campaign::class, 'user_id', 'user_id');
    }

    /**
     * User's payouts
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function payouts() {
        return $this->hasMany(Payout::class, 'user_id', 'user_id');
    }

    /**
     * For Authentication
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getUuidKey();
    }


    /**
     * For Authentication
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
