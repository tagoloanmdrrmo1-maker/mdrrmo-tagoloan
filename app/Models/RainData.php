<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RainData extends Model
{
    use HasFactory;

    protected $table = 'rain_data';

    protected $fillable = [
        'rain_id',
        'dev_id',
        'rain_tips',
    ];

    // Relationships
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function rainfalls()
    {
        return $this->hasMany(Rainfall::class, 'rain_data_id');
    }
}
