<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectEvaluated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $finalScore;
    protected $judgeName;
    protected $feedback;

    /**
     * Constructor con datos de la evaluación
     */
    public function __construct($project, $finalScore, $judgeName, $feedback = null)
    {
        $this->project = $project;
        $this->finalScore = $finalScore;
        $this->judgeName = $judgeName;
        $this->feedback = $feedback;
    }

    /**
     * Canales: correo electrónico + base de datos
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Formato del correo
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Proyecto Evaluado - ' . $this->project->title)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu proyecto ha sido evaluado:')
            ->line('📋 **' . $this->project->title . '**')
            ->line('👨‍⚖️ **Juez:** ' . $this->judgeName)
            ->line('📊 **Calificación Final:** ' . number_format($this->finalScore, 2) . ' puntos');

        // Si hay feedback, agregarlo
        if ($this->feedback) {
            $message->line('💬 **Retroalimentación:**')
                ->line('"' . $this->feedback . '"');
        }

        $message->action('Ver Detalles del Proyecto', route('projects.show', $this->project->id))
            ->line('¡Felicidades por tu participación en el evento!')
            ->salutation('Saludos,')
            ->salutation('Equipo WebAtoon');

        return $message;
    }

    /**
     * Datos para la base de datos
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'project_evaluated',
            'title' => 'Proyecto Evaluado',
            'message' => 'Tu proyecto "' . $this->project->title . '" ha sido evaluado por ' . $this->judgeName . '.',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'final_score' => round($this->finalScore, 2),
            'judge_name' => $this->judgeName,
            'feedback' => $this->feedback,
            'url' => route('projects.show', $this->project->id),
        ];
    }
}
