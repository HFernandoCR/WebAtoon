<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $inviterName;
    protected $eventName;

    /**
     * Constructor: recibe datos del proyecto y quien invita
     */
    public function __construct($project, $inviterName)
    {
        $this->project = $project;
        $this->inviterName = $inviterName;
        // La relación event puede no estar cargada, nos aseguramos
        $this->eventName = $project->event ? $project->event->name : 'Evento WebAtoon';
    }

    /**
     * Canales de notificación: mail + database
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Contenido del correo
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitación a Equipo - ' . $this->project->title)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('**' . $this->inviterName . '** te ha invitado a unirte a su equipo.')
            ->line('📋 **Proyecto:** ' . $this->project->title)
            ->line('🎯 **Evento:** ' . $this->eventName)
            ->line('Como parte del equipo, podrás colaborar en el desarrollo del proyecto y recibir constancias de participación.')
            ->action('Ver Invitación', route('student.team'))
            ->line('Puedes aceptar o rechazar esta invitación desde tu panel de estudiante.')
            ->salutation('Saludos,')
            ->salutation('Equipo WebAtoon');
    }

    /**
     * Datos guardados en tabla notifications
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'team_invitation',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'event_name' => $this->eventName,
            'inviter_name' => $this->inviterName,
            'url' => route('student.team'),
        ];
    }
}
