<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'brgy_location',
        'contact_num',
        'position',
    ];

    // Relationships
    public function messages()
    {
        return $this->hasMany(Message::class, 'contact_id');
    }
}

