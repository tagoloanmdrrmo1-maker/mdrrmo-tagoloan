<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    // Use dev_id as primary key
    protected $primaryKey = 'dev_id';

    // dev_id is a string, not auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'dev_id',
        'serial_number',
        'dev_location',
        'latitude',
        'longitude',
        'date_installed',
        'status',
    ];

    // Relationships
    public function rainData()
    {
        return $this->hasMany(RainData::class, 'device_id');
    }
}
