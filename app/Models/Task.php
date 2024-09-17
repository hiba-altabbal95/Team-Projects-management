<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','title', 'description', 'status', 'priority', 'date_due'];

    public function Project()
    {
       return $this->belongsTo(Project::class);
    }

}
