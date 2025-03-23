<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Application;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function endorsements()
    {
        return $this->belongsToMany(Skill::class, 'endorsements', 'endorsed_user_id', 'endorser_id')
            ->withPivot('skills_id');
    }

    public function initials()
    {
        $names = explode(' ', $this->name);
        $initials = strtoupper(substr($names[0] ?? '', 0, 1) . substr($names[1] ?? '', 0, 1));
        return $initials ?: 'U';
    }

    // Dashboard data methods
    public function getDashboardStats()
    {
        return [
            'total_applications' => $this->applications()->count(),
            'total_skills' => $this->skills()->count(),
            'total_events' => $this->events()->count(),
            'recent_jobs' => $this->jobs()->latest()->take(5)->get(),
            'endorsement_count' => $this->endorsements()->count()
        ];
    }

    public function getRecentActivity()
    {
        return [
            'recent_applications' => $this->applications()->latest()->take(5)->get(),
            'recent_events' => $this->events()->latest()->take(5)->get(),
            'recent_endorsements' => $this->endorsements()->latest()->take(5)->get()
        ];
    }

    public function getProfileCompleteness()
    {
        $total = 5; // Total number of profile sections
        $completed = 0;
        
        if ($this->profile) $completed++;
        if ($this->skills()->exists()) $completed++;
        if ($this->experiences()->exists()) $completed++;
        if ($this->educations()->exists()) $completed++;
        if ($this->endorsements()->exists()) $completed++;
        
        return ($completed / $total) * 100;
    }
}