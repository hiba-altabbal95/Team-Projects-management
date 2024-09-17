<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function Tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function Users()
    {
        return $this->belongsToMany(User::class,'project_user')->withPivot('role', 'contribution_hours')->withTimestamps();
    }
    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany();
    }

    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany();
    }

   //function to execute query return last task created with priority is high and title is backend  
    public function latestHighPriorityBackendTask()
    {
        return $this->hasOne(Task::class)->ofMany([
            'created_at' => 'max'
        ], function ($query) {
            $query->where('priority', 'high')->where('title', 'backend');
        });
    }


    //function to execute query return oldest task created with priority is high and title is backend
    public function oldestHighPriorityBackendTask()
    {
        return $this->hasOne(Task::class)->ofMany([
            'created_at' => 'min'
        ], function ($query) {
            $query->where('priority', 'high')->where('title', 'backend');
        });
    }
}
