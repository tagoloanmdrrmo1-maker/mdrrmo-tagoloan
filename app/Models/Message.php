<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'mes_id';

    protected $fillable = [
        'intensity_level',
        'contact_id',
        'brgy_location',
        'contact_num',
        'text_desc',
        'status',
        'date_created',
        'user_id',
        'device_serial',
    ];

    // Relationships
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_serial', 'serial_number');
    }
    public function rainfall()
    {
        return $this->belongsTo(Rainfall::class, 'intensity_level', 'intensity_level');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'contact_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
