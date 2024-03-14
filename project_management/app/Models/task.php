<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $fillable = ['project_id','task_name', 'task_hours']; // Add 'task_name' here

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
