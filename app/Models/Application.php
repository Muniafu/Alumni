<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    protected $fillable = ['job_id', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // New frontend-related functions
    public static function getUserApplications($userId)
    {
        return self::where('user_id', $userId)->with('job')->get();
    }

    public static function createApplication($jobId, $userId)
    {
        return self::create([
            'job_id' => $jobId,
            'user_id' => $userId,
            'status' => 'pending'
        ]);
    }

    public static function updateApplicationStatus($id, $status)
    {
        $application = self::find($id);
        if ($application) {
            $application->status = $status;
            $application->save();
            return true;
        }
        return false;
    }

    public static function deleteApplication($id)
    {
        return self::destroy($id);
    }
}
