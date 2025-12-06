<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'advisor_id',
        'title',
        'description',
        'category',
        'repository_url',
        'status'
    ];

    // Relación: Pertenece a un Estudiante
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Pertenece a un Evento
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Miembros invitados (aceptados o pendientes)
    public function members()
    {
        return $this->hasMany(\App\Models\ProjectMember::class);
    }
    
    // Solo los aceptados (para contar integrantes)
    public function acceptedMembers()
    {
        return $this->members()->where('status', 'accepted');
    }

    // Relación Muchos a Muchos con Jueces
    public function judges()
    {
        return $this->belongsToMany(User::class, 'project_judge')
                    ->withPivot('score', 'feedback', 'score_document', 'score_presentation', 'score_demo')
                    ->withTimestamps();
    }



    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }
}