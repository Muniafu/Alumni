<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'status'
    ];

    public function attendes()
    {
        return $this->belongsToMany(User::class, 'event_attendes');
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    // Frontend interaction methods
    public function registerForEvent($userId)
    {
        if (!$this->attendes()->where('user_id', $userId)->exists()) {
            $this->attendes()->attach($userId);
            return ['success' => true, 'message' => 'Successfully registered for event'];
        }
        return ['success' => false, 'message' => 'Already registered for this event'];
    }

    public function cancelRegistration($userId)
    {
        if ($this->attendes()->where('user_id', $userId)->exists()) {
            $this->attendes()->detach($userId);
            return ['success' => true, 'message' => 'Successfully cancelled registration'];
        }
        return ['success' => false, 'message' => 'Not registered for this event'];
    }

    public function getEventDetails()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'attendees_count' => $this->attendes()->count(),
            'status' => $this->status
        ];
    }
}
