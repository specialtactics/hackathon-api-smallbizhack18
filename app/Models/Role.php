<?php

namespace App\Models;

class Role extends BaseModel
{
    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SOCIALITE = 'socialite';
    public const ROLE_BUSINESS = 'business';

    public const ALL_ROLES = [
        self::ROLE_ADMIN => 'Administrator User',
        self::ROLE_SOCIALITE => 'Socialite user',
        self::ROLE_BUSINESS => 'Business user',
    ];

    /**
     * Which users frontend can create
     */
    public const FRONTEND_ALLOWED_TO_CREATE = [
        self::ROLE_SOCIALITE,
        self::ROLE_BUSINESS,
    ];

    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'role_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'role_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

}
