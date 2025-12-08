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
    
    %% --- CARRIL 1: ESTUDIANTE ---
    subgraph S_Estudiante [Estudiante - Lider]
        direction TB
        E1[Registrarse e Iniciar Sesion]:::process
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
        G1[Revisar Solicitud de Proyecto]:::process
        G2{Aprobar Proyecto?}:::decision
        G3[Cambiar Estado a Aprobado]:::process
        G4[Cambiar Estado a Rechazado]:::process
        G5[Asignar Jueces Disponibles]:::process
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
    %% 1. Inicio y Registro
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
    style S_Estudiante fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px,color:#000
    style S_Sistema fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px,color:#000
    style S_Gestor fill:#fff3e0,stroke:#ef6c00,stroke-width:2px,color:#000
    style S_Juez fill:#e3f2fd,stroke:#1565c0,stroke-width:2px,color:#000
```
---
© 2025 WebAtoon. Todos los derechos reservados.
