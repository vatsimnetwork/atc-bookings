<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int api_key_id
 * @property string callsign
 * @property int cid
 * @property string start
 * @property string end
 * @property string type
 * @property string created_at
 * @property string updated_at
 */
class Booking extends Model
{
    public $table = 'bookings';
    protected $fillable = [
        'callsign', 'cid', 'start', 'end', 'type'
    ];
}
