<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    // Existing relationships
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    // Frontend-related functions
    public static function createExperience($data)
    {
        try {
            $experience = self::create($data);
            return ['success' => true, 'message' => 'Experience added successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to add experience'];
        }
    }

    public function updateExperience($data)
    {
        try {
            $this->update($data);
            return ['success' => true, 'message' => 'Experience updated successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update experience'];
        }
    }

    public function deleteExperience()
    {
        try {
            $this->delete();
            return ['success' => true, 'message' => 'Experience deleted successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete experience'];
        }
    }

    public static function getUserExperiences($profileId)
    {
        return self::where('profile_id', $profileId)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }
}
