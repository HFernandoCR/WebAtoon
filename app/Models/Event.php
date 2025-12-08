<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manager_id',
        'category_id',
        'description',
        'start_date',
        'end_date',
        'location',
        'status'
    ];

    /**
     * Relación: Un evento pertenece a un Encargado (User)
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Un evento tiene muchos proyectos
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}