<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'requestor_id',
        'requestor_type',
        'report_type',
        'organization',
        'start_date',
        'end_date',
        'purpose',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the requestor that belongs to the report.
     */
    public function requestor()
    {
        return $this->belongsTo(Requestor::class, 'requestor_id', 'requestor_id');
    }

    /**
     * Get the formatted date range.
     */
    public function getDateRangeAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
        } elseif ($this->start_date) {
            return 'From ' . $this->start_date->format('M d, Y');
        } elseif ($this->end_date) {
            return 'Until ' . $this->end_date->format('M d, Y');
        }
        return 'No date range';
    }

    /**
     * Get the requestor name based on type.
     */
    public function getRequestorNameAttribute()
    {
        if ($this->requestor_type === 'old_requestor' && $this->requestor) {
            return $this->requestor->full_name;
        } elseif ($this->requestor_type === 'new_requestor') {
            // If we stored individual name fields for new requestor, we'd access them here
            // For now, we can use the organization field or extend the table
            return $this->organization ? "New Requestor ({$this->organization})" : 'New Requestor';
        }
        return 'No Requestor';
    }
}