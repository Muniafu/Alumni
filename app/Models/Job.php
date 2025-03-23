<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'salary',
        'location',
        'employer_id',
        'status'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Dashboard display methods
    public function getActiveJobs()
    {
        return $this->where('status', 'active')->latest()->get();
    }

    public function getJobsByEmployer($employerId)
    {
        return $this->where('employer_id', $employerId)->latest()->get();
    }

    public function searchJobs($keyword)
    {
        return $this->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('location', 'LIKE', "%{$keyword}%")
                    ->latest()
                    ->get();
    }

    public function getApplicationStats()
    {
        return $this->withCount('applications')->get();
    }
}
