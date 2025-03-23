<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    protected $fillable = ['status', 'feedback', 'user_id', 'endorsed_item_id', 'endorsed_item_type'];

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    // Dashboard functions
    public function getPendingEndorsements()
    {
        return $this->where('status', 'pending')->get();
    }

    public function updateEndorsementStatus($id, $status, $feedback)
    {
        return $this->where('id', $id)
            ->update([
                'status' => $status,
                'feedback' => $feedback
            ]);
    }

    public function getEndorsementsByUser($userId)
    {
        return $this->where('user_id', $userId)->get();
    }

    public function getUserEndorsementStats($userId)
    {
        return [
            'approved' => $this->where('user_id', $userId)->where('status', 'approved')->count(),
            'pending' => $this->where('user_id', $userId)->where('status', 'pending')->count(),
            'rejected' => $this->where('user_id', $userId)->where('status', 'rejected')->count()
        ];
    }

}
