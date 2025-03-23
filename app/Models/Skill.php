<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
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

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

}
