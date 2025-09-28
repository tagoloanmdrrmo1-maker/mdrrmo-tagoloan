<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestor extends Model
{
    use HasFactory;

    protected $table = 'requestors';
    protected $primaryKey = 'requestor_id';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'organization',
    ];

    /**
     * Get the reports for the requestor.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'requestor_id', 'requestor_id');
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }
}