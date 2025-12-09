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

  1. Estudiante: Crear Proyecto
  ```mermaid
sequenceDiagram
    actor Estudiante
    participant ProjectController
    participant Evento as Modelo Evento
    participant Proyecto as Modelo Proyecto
    participant Notificacion as Modelo Notificacion
    participant BD as BaseDeDatos

    Estudiante->>ProjectController: POST /projects (store)
    
    ProjectController->>Proyecto: Verificar si usuario ya tiene proyecto activo
    alt Ya tiene proyecto activo
        ProjectController-->>Estudiante: Error: Ya tienes un proyecto en curso
    else No tiene proyecto activo
        ProjectController->>Evento: findOrFail(event_id)
        Evento-->>ProjectController: Datos del Evento
        
        ProjectController->>ProjectController: Validar Fecha Actual vs (start_date, end_date)
        
        alt Evento no activo / fuera de fecha
            ProjectController-->>Estudiante: Error: El evento no está activo
        else Evento Válido
            ProjectController->>Proyecto: create(título, descripción, categoría, etc.)
            Proyecto->>BD: Insertar Proyecto
            BD-->>Proyecto: ID Proyecto
            
            par Notificaciones
                ProjectController->>Notificacion: Notificar Gestor de Eventos (Nuevo Proyecto)
                ProjectController->>Notificacion: Notificar Asesor (si fue seleccionado)
                ProjectController->>Notificacion: Notificar Administradores
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
    participant Proyecto as Modelo Proyecto
    participant Miembro as Modelo ProjectMember
    participant Usuario as Modelo Usuario
    participant SistemaNotif as Sistema Notificaciones

    Lider->>TeamController: POST /team/invite (email)
    TeamController->>Proyecto: Obtener Proyecto del Líder
    
    rect rgb(245, 245, 245)
        note right of TeamController: Validaciones de Negocio
        TeamController->>Miembro: Contar miembros aceptados (Máx 5)
        TeamController->>Usuario: Buscar por email (Rol: Estudiante)
        TeamController->>Proyecto: Verificar si invitado ya lidera otro proyecto
        TeamController->>Miembro: Verificar si invitado ya pertenece a otro equipo
    end
    
    alt Alguna Validación Falla
        TeamController-->>Lider: Mensaje de Error (Feedback específico)
    else Validación Exitosa
        TeamController->>Miembro: firstOrCreate(status: pendiente)
        
        alt Invitación ya existente
            TeamController-->>Lider: Error: Usuario ya invitado o miembro
        else Nueva Invitación Creada
            Miembro->>SistemaNotif: Enviar Notificación (Email / BD)
            SistemaNotif-->>Usuario: Recibir Invitación
            TeamController-->>Lider: Mensaje de Éxito
        end
    end
```
3. Gestor de Eventos: Asignar Juez
```mermaid
sequenceDiagram
    actor Gestor as Gestor de Eventos
    participant EventManager as EventManagerController
    participant Evento as Modelo Evento
    participant UsuarioJuez as Modelo Usuario (Juez)
    participant Proyecto as Modelo Proyecto
    participant Notificacion as Modelo Notificacion
    participant BD as BaseDeDatos

    Gestor->>EventManager: POST /events/{id}/add-judge (judge_id)
    
    EventManager->>Evento: Verificar propiedad (manager_id) y estado activo
    alt No autorizado o Evento Finalizado
        EventManager-->>Gestor: 403 Prohibido
    else Validaciones Correctas
        EventManager->>Evento: Verificar Cantidad Jueces (< 3)
        EventManager->>UsuarioJuez: Verificar Rol 'judge'
        
        alt Máximo de jueces o Rol inválido
            EventManager-->>Gestor: Error con mensaje
        else Todo Correcto
            note right of EventManager: Vinculación Principal
            EventManager->>Evento: judges()->syncWithoutDetaching(judge_id)
            Evento->>BD: Registrar en event_judge
            
            note right of EventManager: Propagación a Proyectos
            loop Por cada proyecto en el evento
                EventManager->>Proyecto: judges()->syncWithoutDetaching(judge_id)
                Proyecto->>BD: Registrar en project_judge
            end
            
            EventManager->>Notificacion: create(asignacion_juez_evento)
            Notificacion-->>UsuarioJuez: "Has sido asignado al evento X"
            
            EventManager-->>Gestor: Mensaje de Éxito
        end
    end
```
4. Juez: Evaluar Proyecto
```mermaid
sequenceDiagram
    actor Juez
    participant JudgeCtrl as JudgeController
    participant Proyecto as Modelo Proyecto
    participant Pivote as Tabla Pivote (project_judge)
    participant Ranking as RankingService
    participant Notificacion as Modelo Notificacion

    Juez->>JudgeCtrl: POST /judge/evaluate/{id}
    JudgeCtrl->>Proyecto: Verificar asignación (Juez -> Proyecto)
    
    alt No asignado
        JudgeCtrl-->>Juez: 403 Prohibido
    else Asignado
        rect rgb(255, 240, 240)
            note right of JudgeCtrl: Validación de Requisitos
            JudgeCtrl->>Proyecto: Verificar repository_url o deliverables
            alt Sin Entregables/Repo
                JudgeCtrl-->>Juez: Error: "El equipo no ha subido entregables"
            end
            JudgeCtrl->>Proyecto: Verificar Fechas del Evento
        end

        JudgeCtrl->>JudgeCtrl: Calcular Score Final (Doc + Pres + Demo)
        
        JudgeCtrl->>Pivote: updateExistingPivot(scores, feedback)
        
        par Notificaciones y Ranking
            JudgeCtrl->>Notificacion: Notificar Estudiante (ProyectoEvaluado)
            
            note right of JudgeCtrl: Lógica de Ranking
            JudgeCtrl->>Ranking: calculateProjectAverage(proyecto)
            Ranking->>Proyecto: update(average_score)
            
            JudgeCtrl->>Ranking: updateEventRankings(event_id)
            Ranking->>Proyecto: Recalcular posiciones (1, 2, 3...)
            
            JudgeCtrl->>Notificacion: Notificar Gestor de Eventos (Evaluación Completada)
        end
        
        JudgeCtrl-->>Juez: Redirigir a Dashboard (Éxito)
    end
```
5. Administrador: Crear Nuevo Evento
```mermaid
sequenceDiagram
    actor Admin as Administrador
    participant EventController
    participant Usuario as Modelo Usuario
    participant Evento as Modelo Evento
    participant Notificacion as Modelo Notificacion
    participant BD as BaseDeDatos

    Admin->>EventController: POST /events (store)
    
    EventController->>Usuario: find(manager_id)
    
    rect rgb(240, 240, 255)
        note right of EventController: Validaciones de Gestor
        alt Usuario no existe o no es Gestor
            EventController-->>Admin: Error: "El usuario no tiene rol de Gestor"
        end
        
        EventController->>Evento: Verificar si Gestor ya tiene evento ACTIVO
        alt Ya tiene evento activo
            EventController-->>Admin: Error: "Este gestor ya administra un evento activo"
        end
    end
    
    alt Validaciones Exitosas
        EventController->>Evento: create(request->all())
        Evento->>BD: Insertar Evento
        BD-->>Evento: ID Evento
        
        EventController->>Notificacion: create(event_assigned)
        Notificacion-->>Usuario: "Se te ha asignado el evento X"
        
        EventController-->>Admin: Redirigir a Lista (Éxito)
    end
```
    
  ## Diagrama de Flujo
```mermaid
flowchart TB
    %% ==========================================
    %% DEFINICION DE CLASES Y ESTILOS
    %% ==========================================
    classDef startend fill:#f96,stroke:#333,stroke-width:3px,shape:circle,color:black;
    classDef process fill:#fff,stroke:#333,stroke-width:1px,color:black;
    classDef decision fill:#fff9c4,stroke:#fbc02d,stroke-width:2px,shape:diamond,color:black,text-align:center;
    classDef system fill:#e3f2fd,stroke:#1565c0,stroke-width:2px,stroke-dasharray: 5 5,color:#0d47a1;
    classDef data fill:#f3e5f5,stroke:#7b1fa2,stroke-width:1px,shape:parallelogram,color:black;

    %% ==========================================
    %% NODOS PRINCIPALES
    %% ==========================================
    Inicio((Inicio)):::startend
    Fin((Fin)):::startend

    %% ==========================================
    %% CARRILES (SUBGRAPHS)
    %% ==========================================

    %% --- CARRIL 0: ADMINISTRADOR ---
    subgraph S_Admin [Administrador]
        direction TB
        A1[Iniciar Sesion Admin]:::process
        A2[Crear Nuevo Evento]:::process
        A3[Asignar Gestor de Eventos]:::process
        A4[Gestionar Usuarios]:::process
    end

    %% --- CARRIL 1: ESTUDIANTE ---
    subgraph S_Estudiante [Estudiante - Lider]
        direction TB
        E1[Registrarse / Login]:::process
        E2[Buscar Eventos Activos]:::process
        E3[Inscribir Nuevo Proyecto]:::process
        E4[Invitar Miembros al Equipo]:::process
        E5[Subir Entregables y Avances]:::process
        E6[Consultar Resultados Finales]:::process
        E7[Descargar Constancia]:::process
    end

    %% --- CARRIL 2: PLATAFORMA ---
    subgraph S_Sistema [Sistema WebAtoon]
        direction TB
        Sys0[Notificar Asignacion a Gestor]:::system
        Sys1{Credenciales Validas?}:::decision
        Sys2{Fecha Inscripcion Valida?}:::decision
        Sys3>Guardar Proyecto en BD]:::data
        Sys4[Notificar al Gestor]:::system
        Sys5[Enviar Correos de Invitacion]:::system
        Sys6[Notificar Aprobacion o Rechazo]:::system
        Sys7{Evento en Curso?}:::decision
        Sys8[Registrar Evaluaciones]:::system
        Sys9[Calcular Ranking Automatico]:::system
        Sys10[Generar Certificados PDF]:::system
    end

    %% --- CARRIL 3: GESTOR DE EVENTOS ---
    subgraph S_Gestor [Gestor de Eventos]
        direction TB
        G0[Recibir Notificacion de Evento]:::process
        G1[Revisar Solicitud de Proyecto]:::process
        G2{Aprobar Proyecto?}:::decision
        G3[Cambiar Estado a Aprobado]:::process
        G4[Cambiar Estado a Rechazado]:::process
        G5[Asignar Jueces al Evento]:::process
        G6[Cerrar Evento]:::process
    end

    %% --- CARRIL 4: JUEZ ---
    subgraph S_Juez [Juez]
        direction TB
        J1[Recibir Notificacion de Asignacion]:::process
        J2[Revisar Proyecto y Entregables]:::process
        J3[Evaluar Documento y Presentacion]:::process
        J4[Enviar Feedback]:::process
    end

    %% ==========================================
    %% CONEXIONES Y FLUJO
    %% ==========================================
    
    %% 0. Inicio Administrativo
    Inicio --> A1
    A1 --> A2
    A2 --> A3
    A3 --> Sys0
    Sys0 --> G0
    
    %% 1. Inicio Estudiante
    Inicio --> E1
    E1 --> Sys1
    Sys1 -- No --> E1
    Sys1 -- Si --> E2
    
    %% 2. Inscripcion
    E2 --> E3
    E3 --> Sys2
    Sys2 -- No --> E2
    Sys2 -- Si --> Sys3
    Sys3 --> Sys4
    
    %% 3. Aprobacion del Gestor
    G0 --> G1
    Sys4 --> G1
    G1 --> G2
    G2 -- No --> G4
    G4 --> Sys6
    Sys6 --> Fin
    
    G2 -- Si --> G3
    G3 --> Sys6
    Sys6 --> E4
    
    %% 4. Formacion de Equipo
    E4 --> Sys5
    Sys5 --> E5
    
    %% 5. Gestion y Asignacion
    E5 --> Sys7
    Sys7 -- Si --> G5
    G5 --> J1
    
    %% 6. Evaluacion
    J1 --> J2
    J2 --> J3
    J3 --> J4
    J4 --> Sys8
    
    %% 7. Cierre y Resultados
    Sys8 --> Sys9
    Sys9 --> G6
    G6 --> E6
    E6 --> Sys10
    Sys10 --> E7
    E7 --> Fin

    %% ==========================================
    %% AJUSTES VISUALES
    %% ==========================================
    style S_Admin fill:#ffebee,stroke:#c62828,stroke-width:2px,color:#000
    style S_Estudiante fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px,color:#000
    style S_Sistema fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px,color:#000
    style S_Gestor fill:#fff3e0,stroke:#ef6c00,stroke-width:2px,color:#000
    style S_Juez fill:#e3f2fd,stroke:#1565c0,stroke-width:2px,color:#000
```

---
© 2025 WebAtoon. Todos los derechos reservados.
