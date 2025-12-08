# WebAtoon - Sistema de Gestión de Eventos Académicos

WebAtoon es una plataforma integral diseñada para la administración, inscripción y evaluación de proyectos en eventos académicos (como ferias de ciencias, exposiciones de ingeniería, hackathons, etc.).

## Características Principales

- **Gestión de Eventos**: Creación y administración de múltiples eventos simultáneos.
- **Roles y Permisos**: Sistema robusto con roles diferenciados (Admin, Gestor, Juez, Asesor, Estudiante).
- **Inscripción de Proyectos**: Los estudiantes pueden inscribir sus proyectos, seleccionar asesores y subir entregables.
- **Evaluación Digital**: Los jueces asignados pueden evaluar proyectos y dar feedback directamente en la plataforma.
- **Notificaciones**: Sistema de alertas para mantener informados a todos los usuarios sobre cambios importantes.
- **Certificados**: Generación de constancias de participación (Próximamente).

## Estructura del Proyecto

El sistema está construido sobre **Laravel 11** y sigue una arquitectura MVC clásica.

### Roles del Sistema
1.  **Administrador (Admin)**: Control total del sistema. Crea usuarios y eventos.
2.  **Gestor de Eventos (Event Manager)**: Responsable de la logística de un evento específico. Aprueba proyectos y asigna jueces.
3.  **Juez (Judge)**: Experto invitado que evalúa los proyectos asignados.
4.  **Asesor (Advisor)**: Profesor o mentor que guía a los estudiantes.
5.  **Estudiante (Student)**: Participante que inscribe proyectos y sube avances.

### Módulos Clave
-   **Auth**: Registro y autenticación segura.
-   **Proyectos**: CRUD completo de proyectos con estados (Pendiente, Aprobado, Rechazado).
-   **Entregables**: Carga y descarga de archivos (PDF, ZIP) para evidenciar el progreso.
-   **Evaluaciones**: Formulario de calificación con puntaje y retroalimentación.

## Instalación y Configuración

Para configurar el proyecto automáticamente, sigue estos pasos:

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/HFernandoCR/WebAtoon.git
   cd WebAtoon
   ```

2. Ejecutar el script de configuración:

   **Windows:**
   Haz doble clic en `setup.bat` o ejecuta en la terminal:
   ```cmd
   setup.bat
   ```

   **Linux / Mac:**
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

   *Este script instalará dependencias (PHP/Node), configurará el `.env`, generará la key, configurará la base de datos y correrá las migraciones.*

3. **Usuarios de Prueba**:
   Para poblar la base de datos con usuarios de prueba para cada rol, ejecuta:
   ```bash
   php artisan tinker
   include 'create_test_users.php';
   ```

## Tecnologías

-   **Backend**: Laravel 11, PHP 8.2+
-   **Frontend**: Blade Templates, TailwindCSS (vía Vite)
-   **Base de Datos**: MySQL
-   **Control de Versiones**: Git

  ## Diagrama de Secuencias
  ```mermaid
sequenceDiagram
    actor Estudiante
    participant ProjectController
    participant Modelo Evento
    participant Modelo Proyecto
    participant Modelo Notificacion
    participant BaseDeDatos

    Estudiante->>ProjectController: POST /projects (store)
    
    ProjectController->>Modelo Proyecto: Verificar si ya tiene proyecto activo
    alt Ya tiene proyecto activo
        ProjectController-->>Estudiante: Error: Ya tienes un proyecto en curso
    else No tiene proyecto activo
        ProjectController->>Modelo Evento: findOrFail(event_id)
        Modelo Evento-->>ProjectController: Datos del Evento
        
        ProjectController->>ProjectController: Validar Fecha y Estado (Inscripciones abiertas)
        
        alt Fecha/Estado Inválido
            ProjectController-->>Estudiante: Error: El evento no está activo
        else Evento Válido
            ProjectController->>Modelo Proyecto: create(datos)
            Modelo Proyecto->>BaseDeDatos: Insertar Proyecto
            BaseDeDatos-->>Modelo Proyecto: Proyecto Creado
            
            par Notificar Gestor de Eventos
                ProjectController->>Modelo Notificacion: create(nuevo_proyecto_registrado)
            and Notificar Asesor (si fue seleccionado)
                ProjectController->>Modelo Notificacion: create(asesor_asignado)
            and Notificar Administradores
                ProjectController->>Modelo Notificacion: create(nuevo_proyecto)
            end
            
            ProjectController-->>Estudiante: Redirigir a Índice (Éxito)
        end
    end
```
2. Estudiante: Invitar Miembro al Equipo
```mermaid
sequenceDiagram
    actor Lider as Estudiante (Líder)
    participant TeamController
    participant Modelo Proyecto
    participant Modelo ProjectMember
    participant Modelo Usuario
    participant Sistema Notificaciones

    Lider->>TeamController: POST /team/invite (email)
    TeamController->>Modelo Proyecto: Obtener Proyecto del Líder
    
    rect rgb(240, 240, 240)
        note right of TeamController: Validaciones
        TeamController->>Modelo ProjectMember: Contar miembros aceptados (< 5)
        TeamController->>Modelo Usuario: Buscar usuario por email (rol: estudiante)
        TeamController->>Modelo Proyecto: Verificar si el invitado ya lidera un proyecto
        TeamController->>Modelo ProjectMember: Verificar si el invitado ya está en otro equipo
    end
    
    alt Validación Falla
        TeamController-->>Lider: Mensaje de Error
    else Validación Exitosa
        TeamController->>Modelo ProjectMember: firstOrCreate(status: pendiente)
        
        alt Ya invitado o Miembro
            TeamController-->>Lider: Error: Ya invitado/miembro
        else Nueva Invitación
            Modelo ProjectMember->>Sistema Notificaciones: Enviar Notificación (Email/BD)
            Sistema Notificaciones-->>Modelo Usuario (Invitado): Recibir Invitación
            TeamController-->>Lider: Mensaje de Éxito
        end
    end
```
3. Gestor de Eventos: Asignar Juez
```mermaid
sequenceDiagram
    actor Gestor as Gestor de Eventos
    participant EventManagerController
    participant Modelo Evento
    participant Modelo Usuario (Juez)
    participant Modelo Proyecto
    participant Modelo Notificacion
    participant BaseDeDatos

    Gestor->>EventManagerController: POST /projects/{id}/add-judge
    EventManagerController->>Modelo Evento: Verificar que el Gestor sea dueño del Evento
    
    alt No es Dueño / Evento Finalizado
        EventManagerController-->>Gestor: 403 Prohibido
    else Es Dueño y Evento Activo
        EventManagerController->>Modelo Usuario (Juez): Verificar rol 'judge'
        EventManagerController->>Modelo Usuario (Juez): Verificar Disponibilidad (No ocupado en otro evento activo)
        
        alt Rol Inválido u Ocupado
            EventManagerController-->>Gestor: Error: Juez no válido u ocupado
        else Disponible
            EventManagerController->>Modelo Proyecto: judges()->syncWithoutDetaching()
            Modelo Proyecto->>BaseDeDatos: Vincular Juez al Proyecto
            
            EventManagerController->>Modelo Notificacion: create(asignacion_juez)
            Modelo Notificacion-->>Modelo Usuario (Juez): Notificar Juez
            
            EventManagerController-->>Gestor: Mensaje de Éxito
        end
    end
```
4. Juez: Evaluar Proyecto
```mermaid
sequenceDiagram
    actor Juez
    participant JudgeController
    participant Modelo Proyecto
    participant Tabla Pivote (project_judge)
    participant ServicioRanking
    participant Modelo Notificacion

    Juez->>JudgeController: POST /judge/evaluate/{id}
    JudgeController->>Modelo Proyecto: Verificar Asignación
    
    alt No Asignado
        JudgeController-->>Juez: 403 Prohibido
    else Asignado
        JudgeController->>JudgeController: Validar Puntajes (Doc, Presentación, Demo)
        JudgeController->>JudgeController: Calcular Puntaje Final (Suma)
        
        JudgeController->>Tabla Pivote (project_judge): updateExistingPivot(scores, feedback)
        
        JudgeController->>Modelo Proyecto: Notificar Estudiante (ProyectoEvaluado)
        
        rect rgb(230, 245, 255)
            note right of JudgeController: Actualización de Ranking Automática
            JudgeController->>ServicioRanking: calculateProjectAverage(proyecto)
            JudgeController->>ServicioRanking: updateEventRankings(event_id)
        end
        
        JudgeController->>Modelo Notificacion: Notificar Gestor de Eventos
        
        JudgeController-->>Juez: Redirigir a Dashboard (Éxito)
    end
```

---
© 2025 WebAtoon. Todos los derechos reservados.
