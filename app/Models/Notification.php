<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the notification (if any)
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Notification types
    const TYPE_DEVICE_DATA = 'device_data';
    const TYPE_NEW_DEVICE = 'new_device';
    const TYPE_NEW_CONTACT = 'new_contact';
    const TYPE_NEW_REPORT = 'new_report';
    const TYPE_NEW_USER = 'new_user';
    const TYPE_SYSTEM_ALERT = 'system_alert';

    // Notification icons for different types
    const NOTIFICATION_ICONS = [
        self::TYPE_DEVICE_DATA => 'fas fa-microchip',
        self::TYPE_NEW_DEVICE => 'fas fa-plus-circle',
        self::TYPE_NEW_CONTACT => 'fas fa-user-plus',
        self::TYPE_NEW_REPORT => 'fas fa-file-alt',
        self::TYPE_NEW_USER => 'fas fa-user-cog',
        self::TYPE_SYSTEM_ALERT => 'fas fa-exclamation-triangle',
    ];

    // Notification colors for different types
    const NOTIFICATION_COLORS = [
        self::TYPE_DEVICE_DATA => 'bg-blue-100 text-blue-800',
        self::TYPE_NEW_DEVICE => 'bg-green-100 text-green-800',
        self::TYPE_NEW_CONTACT => 'bg-purple-100 text-purple-800',
        self::TYPE_NEW_REPORT => 'bg-orange-100 text-orange-800',
        self::TYPE_NEW_USER => 'bg-indigo-100 text-indigo-800',
        self::TYPE_SYSTEM_ALERT => 'bg-red-100 text-red-800',
    ];

    /**
     * Get the icon for this notification type
     */
    public function getIconAttribute()
    {
        return self::NOTIFICATION_ICONS[$this->type] ?? 'fas fa-bell';
    }

    /**
     * Get the color class for this notification type
     */
    public function getColorClassAttribute()
    {
        return self::NOTIFICATION_COLORS[$this->type] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get the formatted created_at time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Create a new notification
     */
    public static function create(array $attributes = [])
    {
        $attributes['is_read'] = $attributes['is_read'] ?? false;

        return static::query()->create($attributes);
    }

    /**
     * Create device data notification
     */
    public static function createDeviceDataNotification($deviceLocation, $intensityLevel, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_DEVICE_DATA,
            'title' => 'New Rainfall Data Received',
            'message' => "Device at {$deviceLocation} reported {$intensityLevel} rainfall intensity",
            'data' => [
                'device_location' => $deviceLocation,
                'intensity_level' => $intensityLevel,
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create new device notification
     */
    public static function createNewDeviceNotification($deviceLocation, $serialNumber, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_DEVICE,
            'title' => 'New Device Added',
            'message' => "New device added at {$deviceLocation} (Serial: {$serialNumber})",
            'data' => [
                'device_location' => $deviceLocation,
                'serial_number' => $serialNumber,
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create new contact notification
     */
    public static function createNewContactNotification($contactName, $location, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_CONTACT,
            'title' => 'New Contact Added',
            'message' => "New contact {$contactName} added for {$location}",
            'data' => [
                'contact_name' => $contactName,
                'location' => $location,
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create contact updated notification
     */
    public static function createContactUpdatedNotification($contactName, $location, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_CONTACT,
            'title' => 'Contact Updated',
            'message' => "Contact {$contactName} from {$location} has been updated",
            'data' => [
                'contact_name' => $contactName,
                'location' => $location,
                'action' => 'updated',
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create contact deleted notification
     */
    public static function createContactDeletedNotification($contactName, $location, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_CONTACT,
            'title' => 'Contact Deleted',
            'message' => "Contact {$contactName} from {$location} has been deleted",
            'data' => [
                'contact_name' => $contactName,
                'location' => $location,
                'action' => 'deleted',
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create new report notification
     */
    public static function createNewReportNotification($reportType, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_REPORT,
            'title' => 'New Report Generated',
            'message' => "New {$reportType} report has been generated",
            'data' => [
                'report_type' => $reportType,
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create new user notification
     */
    public static function createNewUserNotification($userName, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_NEW_USER,
            'title' => 'New User Added',
            'message' => "New user {$userName} has been added to the system",
            'data' => [
                'user_name' => $userName,
            ],
            'user_id' => $userId,
        ]);
    }

    /**
     * Create system alert notification
     */
    public static function createSystemAlertNotification($alertMessage, $userId = null)
    {
        return self::create([
            'type' => self::TYPE_SYSTEM_ALERT,
            'title' => 'System Alert',
            'message' => $alertMessage,
            'data' => [],
            'user_id' => $userId,
        ]);
    }
}