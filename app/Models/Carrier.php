<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Carrier
 *
 * @property int                             $id
 * @property string|null                     $code
 * @property string|null                     $name
 * @method errors()
 */
class Carrier extends Model
{
    use HasFactory;
    protected $table = 'carriers';



}
