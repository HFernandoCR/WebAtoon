<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DeliverableController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EventManagerController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\AdvisorController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware(['auth', 'verified'])->group(function () {

    //  DASHBOARD GENERAL
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/events/active', [EventController::class, 'activeEvents'])->name('events.active');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll')->middleware('throttle:10,1');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('throttle:30,1');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll')->middleware('throttle:5,1');

    // === RANKINGS (Iteración 4) ===
    Route::get('/rankings', [App\Http\Controllers\RankingController::class, 'index'])->name('rankings.index');
    Route::post('/rankings/{event}/recalculate', [App\Http\Controllers\RankingController::class, 'recalculate'])->name('rankings.recalculate')->middleware(['role:admin|event_manager']);

    // === REPORTES (Iteración Extra) ===
    Route::middleware(['role:admin|event_manager'])->group(function () {
        Route::get('/reports/{event}/excel', [App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('/reports/{event}/pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.pdf');
    });

    // === CONSTANCIAS (Iteración 5) ===
    Route::get('/certificates/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');


    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)->middleware('throttle:60,1');
        Route::resource('events', EventController::class)->middleware('throttle:60,1');
        Route::resource('categories', App\Http\Controllers\CategoryController::class)->middleware('throttle:60,1');
        Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
    });


    // === ZONA ESTUDIANTE ===
    Route::middleware(['role:student'])->group(function () {

        // CRUD Proyectos
        Route::resource('projects', ProjectController::class);

        // Entregables
        Route::resource('deliverables', DeliverableController::class)->only(['index', 'store'])->middleware('throttle:30,1');
        Route::get('deliverables/{deliverable}', [DeliverableController::class, 'download'])->name('deliverables.download');

        // Vistas adicionales
        Route::get('/my-team', [ProjectController::class, 'myTeam'])->name('student.team');
        Route::get('/my-certificates', [ProjectController::class, 'certificates'])->name('student.certificates');

        // Rutas de Equipos e Invitaciones
        Route::get('/my-team', [TeamController::class, 'index'])->name('student.team');
        Route::post('/team/invite', [TeamController::class, 'invite'])->name('team.invite')->middleware('throttle:10,1');
        Route::post('/team/accept/{id}', [TeamController::class, 'accept'])->name('team.accept')->middleware('throttle:20,1');
        Route::post('/team/reject/{id}', [TeamController::class, 'reject'])->name('team.reject')->middleware('throttle:20,1');
        Route::delete('/team/remove/{id}', [TeamController::class, 'remove'])->name('team.remove')->middleware('throttle:10,1');
    });


    // === ZONA GESTOR DE EVENTOS ===
    Route::middleware(['role:event_manager'])->group(function () {

        // Panel principal del gestor (Ver proyectos de su evento)
        Route::get('/event-management', [EventManagerController::class, 'index'])->name('manager.dashboard');

        // Aprobar o Rechazar proyectos
        Route::patch('/event-management/projects/{project}/status', [EventManagerController::class, 'updateStatus'])->name('manager.projects.status')->middleware('throttle:30,1');

        // (Próximamente agregaremos la asignación de jueces aquí)
        // ... rutas anteriores del gestor ...

        // Asignación de Jueces del Evento
        Route::get('/event-management/{event}/judges', [EventManagerController::class, 'eventJudgesView'])->name('manager.event.judges');
        Route::post('/event-management/{event}/judges', [EventManagerController::class, 'addEventJudge'])->name('manager.event.judges.add');
        Route::delete('/event-management/{event}/judges/{judge}', [EventManagerController::class, 'removeEventJudge'])->name('manager.event.judges.remove');

        // Editar Evento (Status)
        Route::get('/event-management/edit', [EventManagerController::class, 'editEvent'])->name('manager.event.edit');
        Route::put('/event-management/update', [EventManagerController::class, 'updateEventStatus'])->name('manager.event.update');

    });


    // === ZONA JUEZ ===
    Route::middleware(['role:judge'])->group(function () {

        // Panel principal (Mis asignaciones)
        Route::get('/judge/dashboard', [JudgeController::class, 'index'])->name('judge.dashboard');

        // Formulario de Evaluación
        Route::get('/judge/evaluate/{project}', [JudgeController::class, 'edit'])->name('judge.evaluate');

        // Guardar Evaluación
        Route::post('/judge/evaluate/{project}', [JudgeController::class, 'update'])->name('judge.store_evaluation')->middleware('throttle:30,1');
    });



    // === ZONA ASESOR ===
    Route::middleware(['role:advisor'])->group(function () {
        Route::get('/advisor/dashboard', [AdvisorController::class, 'index'])->name('advisor.dashboard');
        Route::get('/advisor/certificates', [AdvisorController::class, 'certificates'])->name('advisor.certificates');
    });

});




require __DIR__ . '/auth.php';