<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int cid
 * @property string name
 * @property string key
 * @property string division
 * @property string subdivision
 * @property string created_at
 * @property string updated_at
 */
class ApiKey extends Model
{
    public $table = 'api_keys';
    public $fillable = [
        'name', 'key', 'cid', 'division', 'subdivision'
    ];
}
