# PLAN DE ITERACIONES - WEBATOON (ACTUALIZADO)
## Sistema de Gestión de Eventos Académicos

---

## ITERACIÓN 1: CORRECCIONES CRÍTICAS ✅ COMPLETADA
**Estado**: Finalizada
- Fix 1.1: Eager loading de jueces
- Fix 1.2: Dashboard dinámico
- Fix 1.3: Validación de permisos
- Fix 1.4: Indicadores de ruta activa

---

## ITERACIÓN 2: NOTIFICACIONES Y COMUNICACIÓN (Prioridad Alta)
**Objetivo**: Implementar sistema de notificaciones para mejorar UX
**Duración estimada**: Sprint medio
**Estado**: Pendiente

### Observación del docente incluida:
- "Notificarle al usuario qué debe hacer"
- "Correo de Gmail con SMTP" (configuración básica)

### Tareas:

#### 2.1 Crear sistema de notificaciones en base de datos
- Migration: `create_notifications_table.php`
- Model: `app/Models/Notification.php`
- Campos: `user_id`, `type`, `title`, `message`, `data`, `read_at`, `url`, `timestamps`
- Prioridad: Alta

#### 2.2 Notificación: Invitación a equipo
- Trigger: Cuando un líder invita a un estudiante
- Controlador: `TeamController.php`
- Mensaje: "Has sido invitado al equipo [Nombre Proyecto]"
- Acción: Botón para aceptar/rechazar
- Prioridad: Alta

#### 2.3 Notificación: Asignación de juez
- Trigger: Cuando se asigna un juez a un proyecto
- Controlador: `EventManagerController.php`
- Mensaje: "Se te ha asignado evaluar el proyecto [Nombre]"
- Instrucciones: "Ingresa a 'Evaluar Proyectos' para calificar"
- Prioridad: Alta

#### 2.4 Notificación: Cambio de estado de proyecto
- Trigger: Aprobación/rechazo de proyecto
- Controlador: `EventManagerController.php`
- Mensajes:
  - Aprobado: "Tu proyecto ha sido aprobado. Siguiente paso: formar equipo"
  - Rechazado: "Tu proyecto ha sido rechazado. Motivo: [razón]"
- Prioridad: Alta

#### 2.5 Notificación: Instrucciones para nuevos usuarios
- Trigger: Primer login después de registro
- Mensajes por rol:
  - Student: "Bienvenido. Primero inscribe tu proyecto en un evento activo"
  - Judge: "Bienvenido. Espera a que te asignen proyectos para evaluar"
  - Advisor: "Bienvenido. Los estudiantes podrán seleccionarte como asesor"
  - Event Manager: "Bienvenido. Gestiona proyectos desde tu panel"
- Prioridad: Media

#### 2.6 Configurar envío de emails con Gmail SMTP
- Archivo: `.env`
- Configuración:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=tu-email@gmail.com
  MAIL_PASSWORD=tu-app-password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=tu-email@gmail.com
  MAIL_FROM_NAME="WebAtoon"
  ```
- Crear: `app/Mail/NotificationMail.php`
- Prioridad: Media

#### 2.7 Enviar email además de notificación in-app
- Para eventos críticos:
  - Invitación a equipo
  - Asignación de juez
  - Cambio de estado de proyecto
- Template de email básico con logo
- Prioridad: Baja

#### 2.8 Componente de notificaciones en navbar
- Vista: Dropdown en `layouts/navigation.blade.php`
- Mostrar: Contador y últimas 5 notificaciones
- Botón "Ver todas" → ruta `/notifications`
- Prioridad: Media

#### 2.9 Página de historial de notificaciones
- Ruta: `/notifications`
- Vista: `resources/views/notifications/index.blade.php`
- Controlador: `NotificationController.php`
- Funcionalidad: Marcar como leída, eliminar
- Prioridad: Baja

---

## ITERACIÓN 3: DASHBOARD Y ESTADÍSTICAS (Prioridad Media)
**Objetivo**: Mejorar visualización de datos y reportes
**Duración estimada**: Sprint medio
**Estado**: Pendiente

### Observación del docente incluida:
- "Evaluar eventos" (reportes y estadísticas de eventos)

### Tareas:

#### 3.1 Dashboard Admin: Gráficas de estadísticas
- Vista: `dashboard.blade.php`
- Agregar:
  - Gráfica de usuarios por rol (Chart.js)
  - Gráfica de eventos activos vs finalizados
  - Tabla de últimos usuarios registrados
- Prioridad: Media

#### 3.2 Dashboard Event Manager: Métricas operativas
- Vista: `Manager/dashboard.blade.php`
- Agregar:
  - Distribución de calificaciones (gráfica)
  - Timeline de evaluaciones pendientes
  - Alertas de proyectos sin evaluar próximos a deadline
- Prioridad: Alta

#### 3.3 Módulo: Evaluar Eventos (Admin)
- Ruta: `/admin/events/{event}/evaluate`
- Vista: `Admin/events/evaluate.blade.php`
- Métricas por evento:
  - Total de proyectos inscritos
  - Proyectos aprobados/rechazados/pendientes
  - Distribución por categorías
  - Promedio de calificaciones
  - Tabla de ganadores (top 10)
  - Participación por institución
- Exportar a PDF/Excel
- Prioridad: Alta

#### 3.4 Dashboard Student: Progreso del proyecto
- Vista: Nueva vista `Student/project-dashboard.blade.php`
- Mostrar:
  - Barra de progreso: inscrito → aprobado → evaluado → resultados
  - Checklist de tareas pendientes
  - Estado de invitaciones de equipo
  - Próximas fechas importantes
- Prioridad: Media

#### 3.5 Dashboard Judge: Calendario de evaluaciones
- Vista: `Judge/index.blade.php`
- Agregar:
  - Filtros por estado (evaluado/pendiente)
  - Resumen de evaluaciones completadas vs pendientes
- Prioridad: Baja

#### 3.6 Página de reportes para Admin
- Ruta: `/admin/reports`
- Vista: Nueva vista con reportes descargables (PDF/Excel)
- Reportes:
  - Listado completo de proyectos por evento
  - Resultados finales con ranking
  - Participación por institución
  - Desempeño de jueces (cuántos evaluaron)
- Prioridad: Baja

---

## ITERACIÓN 4: CERTIFICADOS Y CONSTANCIAS (Prioridad Media)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 5: SISTEMA DE ENTREGABLES MEJORADO (Prioridad Media)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 6: GESTIÓN DE EQUIPOS MEJORADA (Prioridad Baja)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 7: GESTIÓN DE SALAS Y HORARIOS (Prioridad Baja)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 8: SEGURIDAD Y VALIDACIONES (Prioridad Alta)
**Objetivo**: Reforzar seguridad y manejo de errores
**Duración estimada**: Sprint corto
**Estado**: Pendiente

### Observación del docente incluida:
- "Cómo validamos que permisos tiene cierto rol (vista)"

### Tareas:

#### 8.1 Directiva Blade personalizada para mostrar permisos
- Archivo: `app/Providers/AppServiceProvider.php`
- Crear directiva: `@haspermission('permiso')`
- Uso en vistas:
  ```blade
  @haspermission('manage-users')
      <a href="{{ route('users.create') }}">Crear Usuario</a>
  @endhaspermission
  ```
- Prioridad: Alta

#### 8.2 Componente de validación de permisos en vistas
- Vista: `resources/views/components/permission-check.blade.php`
- Mostrar mensaje cuando usuario no tiene permiso
- Ejemplo de uso:
  ```blade
  <x-permission-check permission="evaluate-projects">
      Formulario de evaluación aquí
  </x-permission-check>
  ```
- Prioridad: Media

#### 8.3 Página de "Permisos por Rol" (Admin)
- Ruta: `/admin/permissions`
- Vista: Tabla mostrando matriz de roles vs permisos
- Funcionalidad: Admin puede ver (y opcionalmente editar) permisos
- Prioridad: Media

#### 8.4 Middleware para validar permisos en rutas
- Ya implementado con Spatie, pero documentar uso:
  ```php
  Route::middleware(['permission:manage-users'])->group(function () {
      // Rutas protegidas
  });
  ```
- Crear documentación interna
- Prioridad: Baja

#### 8.5 Validación de archivos subidos
- Archivo: `DeliverableController.php`
- Mejoras:
  - Validar contenido real del archivo (no solo extensión)
  - Limitar tipos MIME permitidos
- Prioridad: Alta

#### 8.6 Rate limiting en rutas críticas
- Archivo: `routes/web.php`
- Aplicar throttle en login, registro, subida de archivos
- Prioridad: Alta

#### 8.7 Logging de acciones críticas
- Implementar log de:
  - Creación/eliminación de usuarios
  - Cambios de rol
  - Asignación de jueces
  - Aprobación/rechazo de proyectos
- Herramienta: Laravel Log o `spatie/laravel-activitylog`
- Prioridad: Media

#### 8.8 Validación de relaciones antes de eliminar
- Validar que no haya proyectos activos antes de eliminar evento
- Archivos: `EventController.php`, `UserController.php`
- Prioridad: Media

#### 8.9 Manejo de errores amigable
- Crear páginas de error personalizadas (403, 404, 500)
- Ubicación: `resources/views/errors/`
- Prioridad: Baja

---

## ITERACIÓN 9: RESPONSIVE Y UX (Prioridad Media)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 10: TESTING Y CALIDAD (Prioridad Media)
**Estado**: Pendiente
(Sin cambios - mantiene tareas originales)

---

## ITERACIÓN 11: PREPARACIÓN PARA PRODUCCIÓN (Prioridad Alta)
**Objetivo**: Optimizar y preparar para deploy
**Duración estimada**: Sprint corto
**Estado**: Pendiente

### Observación del docente incluida:
- "Correo de Gmail con SMTP" (configuración producción)

### Tareas:

#### 11.1 Optimización de queries
- Herramienta: Laravel Debugbar
- Revisar todos los controladores usan eager loading
- Prioridad: Alta

#### 11.2 Cache de configuración
- Comandos: config:cache, route:cache, view:cache
- Prioridad: Alta

#### 11.3 Variables de entorno
- Validar `.env.example` esté actualizado
- Documentar variables requeridas en README
- Incluir configuración Gmail SMTP
- Prioridad: Media

#### 11.4 Seeders de producción
- Crear seeders para roles y permisos básicos
- Evitar seeders de datos fake en producción
- Prioridad: Media

#### 11.5 Configuración de correos para producción
- Servicio: Gmail SMTP (dev/staging) → AWS SES / Mailgun (prod)
- Configurar límites de envío
- Templates profesionales de email
- Prioridad: Alta

#### 11.6 Monitoreo y logs
- Herramientas: Laravel Telescope (dev), Sentry (prod)
- Configurar alertas para errores críticos
- Prioridad: Media

#### 11.7 Backup automático
- Paquete: `spatie/laravel-backup`
- Configurar backup diario de BD y archivos
- Destino: AWS S3 o similar
- Prioridad: Alta

---

## ITERACIÓN 12: CRITERIOS DE EVALUACIÓN (NUEVA - Prioridad Alta)
**Objetivo**: Implementar sistema de criterios personalizables
**Duración estimada**: Sprint medio
**Estado**: Pendiente

### Observación del docente incluida:
- "Agregar criterios de evaluación"

### Tareas:

#### 12.1 Modelo de Criterios de Evaluación
- Migration: `create_evaluation_criteria_table.php`
- Campos:
  - `event_id` (cada evento tiene sus criterios)
  - `name` (ej: "Innovación", "Impacto Social")
  - `description`
  - `max_points` (ej: 20)
  - `order` (orden de aparición)
- Model: `app/Models/EvaluationCriterion.php`
- Prioridad: Alta

#### 12.2 CRUD de Criterios (Event Manager)
- Ruta: `/manager/criteria`
- Vistas:
  - `Manager/criteria/index.blade.php`
  - `Manager/criteria/create.blade.php`
  - `Manager/criteria/edit.blade.php`
- Funcionalidad:
  - Crear criterios personalizados
  - Establecer puntaje máximo por criterio
  - Ordenar criterios (drag & drop opcional)
  - Validar que suma total = 100 puntos
- Prioridad: Alta

#### 12.3 Plantillas de Criterios Predefinidas
- Crear seeder con plantillas comunes:
  - "Hackathon Estándar": Innovación (25), Funcionalidad (25), Diseño (20), Presentación (15), Código (15)
  - "Investigación": Metodología (30), Resultados (30), Presentación (20), Bibliografía (20)
  - "Emprendimiento": Innovación (20), Viabilidad (25), Impacto (25), Presentación (15), Modelo de Negocio (15)
- Botón "Usar plantilla" en creación de evento
- Prioridad: Media

#### 12.4 Modificar tabla project_judge
- Migration: Agregar tabla pivot `project_judge_scores`
- Campos:
  - `project_judge_id` (relación con project_judge)
  - `criterion_id`
  - `score` (puntaje dado)
- Cambiar estructura actual de score único
- Prioridad: Alta

#### 12.5 Formulario de Evaluación con Criterios
- Archivo: `Judge/evaluate.blade.php`
- Cambios:
  - Mostrar cada criterio con su puntaje máximo
  - Input numérico por cada criterio
  - Validación: no exceder max_points
  - Calcular total automáticamente
  - Mostrar barra de progreso (ej: 85/100)
- Campo de feedback general (mantener)
- Prioridad: Alta

#### 12.6 Controlador de Evaluación actualizado
- Archivo: `JudgeController.php`
- Método `update()`:
  - Validar cada criterio
  - Guardar scores individuales
  - Calcular total
  - Actualizar score total en project_judge
- Prioridad: Alta

#### 12.7 Vista de Resultados Detallados
- Ruta: `/projects/{project}/results` (Student)
- Mostrar:
  - Tabla con criterios y puntajes por juez
  - Promedio por criterio
  - Total final
  - Gráfica de radar comparando criterios
- Prioridad: Media

#### 12.8 Exportar Resultados con Criterios
- En reportes de Admin
- Excel/PDF con desglose por criterio
- Comparativa entre proyectos
- Prioridad: Baja

---

## RESUMEN DE PRIORIDADES ACTUALIZADO

### Prioridad Alta (Hacer primero):
1. **Iteración 1**: Correcciones críticas ✅ COMPLETADA
2. **Iteración 2**: Notificaciones + SMTP
3. **Iteración 3**: Dashboard y evaluar eventos
4. **Iteración 8**: Seguridad y validación de permisos
5. **Iteración 12**: Criterios de evaluación (NUEVO)
6. **Iteración 11**: Preparación para producción

### Prioridad Media:
7. **Iteración 4**: Certificados
8. **Iteración 5**: Sistema de entregables mejorado
9. **Iteración 9**: Responsive y UX
10. **Iteración 10**: Testing

### Prioridad Baja:
11. **Iteración 6**: Gestión de equipos mejorada
12. **Iteración 7**: Gestión de salas

---

## CRONOGRAMA SUGERIDO ACTUALIZADO

```
Semana 1-2:   Iteración 1 ✅ COMPLETADA
Semana 3-4:   Iteración 2 (Notificaciones + SMTP)
Semana 5-6:   Iteración 12 (Criterios de Evaluación) - NUEVO
Semana 7-8:   Iteración 3 (Dashboards + Evaluar Eventos)
Semana 9-10:  Iteración 8 (Seguridad + Validación Permisos)
Semana 11-12: Iteración 4 (Certificados)
Semana 13-14: Iteración 5 (Entregables)
Semana 15-16: Iteración 9 (Responsive)
Semana 17:    Iteración 10 (Testing)
Semana 18:    Iteración 11 (Producción + SMTP final)
Semana 19+:   Iteraciones 6 y 7 (Opcional)
```

---

## NOTAS IMPORTANTES

### Observaciones del Docente - Estado:
- ✅ "El proyecto a qué evento pertenece" - Ya implementado
- 📋 "Validar permisos de roles en vistas" - Iteración 8
- 📋 "Evaluar eventos" - Iteración 3
- 📋 "Criterios de evaluación" - Iteración 12 (NUEVA)
- 📋 "Notificar qué debe hacer el usuario" - Iteración 2
- 📋 "Gmail con SMTP" - Iteración 2 (básico) + Iteración 11 (producción)

### Próxima Iteración:
**Iteración 2: Notificaciones y SMTP**
