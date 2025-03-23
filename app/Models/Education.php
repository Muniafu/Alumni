<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'degree_name',
        'institution',
        'graduation_year',
        'profile_id',
        'skill_id'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public static function createEducation($data)
    {
        try {
            $education = self::create($data);
            return ['success' => true, 'message' => 'Education added successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to add education'];
        }
    }

    public static function updateEducation($id, $data)
    {
        try {
            $education = self::findOrFail($id);
            $education->update($data);
            return ['success' => true, 'message' => 'Education updated successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update education'];
        }
    }

    public static function deleteEducation($id)
    {
        try {
            $education = self::findOrFail($id);
            $education->delete();
            return ['success' => true, 'message' => 'Education deleted successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete education'];
        }
    }
}
