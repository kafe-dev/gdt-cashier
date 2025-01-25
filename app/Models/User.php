<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model.
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $role
 * @property string $registration_ip
 * @property string|null $remember_token
 * @property int $status
 * @property mixed $email_verified_at
 * @property mixed $last_login_at
 * @property mixed $blocked_at
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string|null $strip_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property int|null $trial_ends_at
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public final const int ROLE_USER = 0;

    public final const int ROLE_ADMIN = 1;

    public final const int ROLE_ACCOUNTANT = 2;

    public final const int ROLE_SUPPORT = 3;

    public final const int ROLE_SELLER = 4;

    public final const int ROLE_SUB_ADMIN = 5;

    public final const array ROLES = [
        self::ROLE_USER => 'User',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_ACCOUNTANT => 'Accountant',
        self::ROLE_SUPPORT => 'Support',
        self::ROLE_SELLER => 'Seller',
        self::ROLE_SUB_ADMIN => 'SubAdmin',
    ];

    public final const int STATUS_INACTIVE = 0;

    public final const int STATUS_ACTIVE = 1;

    public final const int STATUS_BLOCKED = 2;

    public final const array STATUSES = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'registration_ip',
        'status',
        'email_verified_at',
        'last_login_at',
        'blocked_at',
        'strip_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'blocked_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
