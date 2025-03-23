<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    
}
