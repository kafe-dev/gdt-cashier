<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User.
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $roles
 * @property string $remember_token
 * @property int $status
 * @property int|null $email_verified_at
 * @property int|null $last_login_at
 * @property int|null $blocked_at
 * @property int $created_at
 * @property int $updated_at
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_USER = 'ROLE_USER';

    public const ROLES = [
        self::ROLE_ADMIN => 'ROLE_ADMIN',
        self::ROLE_USER => 'ROLE_USER',
    ];

    public const STATUS_INACTIVE = 0;

    public const STATUS_ACTIVE = 1;

    public const STATUS_BLOCKED = 2;

    public const STATUS = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked'
    ];

    /**
     * The table name.
     */
    public $table = 'users';

    /**
     * Turn-on auto timestamp.
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'auth_key',
        'roles',
        'remember_token',
        'status',
        'email_verified_at',
        'last_login_at',
        'blocked_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'auth_key',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Generate the auth key for user.
     *
     * @var string $identifier The keyword for hashing
     *
     * @return string
     */
    public static function generateAuthKey(string $identifier)
    {
        return hash('sha256', $identifier);
    }
}
