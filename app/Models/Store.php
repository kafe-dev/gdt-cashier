<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Store model.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $url
 * @property string|null $description
 * @property int $status
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Store extends Model
{
    public final const int STATUS_INACTIVE = 0;

    public final const int STATUS_ACTIVE = 1;

    public final const int STATUS_DRAFT = 2;

    public final const array STATUSES = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_DRAFT => 'Draft',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'stores';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'description',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
