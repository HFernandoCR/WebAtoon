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

    const STATUS_REGISTRATION = 'registration';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_FINISHED = 'finished';

    public static function getStatuses()
    {
        return [
            self::STATUS_REGISTRATION => 'En Inscripciones',
            self::STATUS_IN_PROGRESS => 'En Curso',
            self::STATUS_FINISHED => 'Finalizado',
        ];
    }

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
}