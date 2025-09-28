<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rainfall extends Model
{
    use HasFactory;

    protected $table = 'rainfalls';

    protected $fillable = [
        'rainfall_id',
        'dev_location',
        'device_id',
        'rain_tips',
        'cumulative_rainfall',
        'intensity_level',
    ];

    // Relationships
    public function rainData()
    {
        return $this->belongsTo(RainData::class, 'rain_data_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'dev_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
