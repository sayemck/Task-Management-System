<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'name', 'priority', 'status'
    ];

    public function project_name()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
