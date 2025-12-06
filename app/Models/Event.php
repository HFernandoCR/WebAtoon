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

    /**
     * Verifica si el evento está dentro de las fechas permitidas para inscripciones
     */
    public function isOpen()
    {
        $now = now()->toDateString();
        return $this->status === 'active' &&
               $now >= $this->start_date &&
               $now <= $this->end_date;
    }

    /**
     * Verifica si el evento ya finalizó
     */
    public function hasEnded()
    {
        return now()->toDateString() > $this->end_date;
    }
}