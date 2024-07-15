<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use app\Http\Controllers\RatingController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\NotificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/home', function () {
    return view('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// page d'accueil
// Route::get('/', function () {
//    return view('welcome');
// });

  


//visiteur
// Routes accessibles aux visiteurs
Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');

//incidents
Route::resource('incidents', IncidentController::class);

//verification selon le role de chacun
Route::middleware(['auth'])->group(function () {
    // Route::resource('incidents', IncidentController::class);
    Route::resource('incidents', IncidentController::class)->except(['create', 'store', 'show']);

    

    

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/create-agent', [AdminController::class, 'createAgent'])->name('admin.create-agent');
        Route::post('/admin/store-agent', [AdminController::class, 'storeAgent'])->name('admin.store-agent');

        Route::get('/historique/admin', [App\Http\Controllers\HistoriqueController::class, 'adminHistorique'])->name('historique.admin');

        Route::get('/admin/agents/{id}/edit', [AdminController::class, 'editAgent'])->name('admin.edit-agent');
        Route::put('/admin/agents/{id}', [AdminController::class, 'updateAgent'])->name('admin.update-agent');
        Route::delete('/admin/agents/{id}', [AdminController::class, 'deleteAgent'])->name('admin.delete-agent');

    });

    
    
    Route::middleware(['auth', 'role:agent'])->group(function () {
        Route::get('/agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');
    Route::post('/agent/incidents/{incident}/assign', [AgentController::class, 'assignIncident'])->name('agent.assign.incident');
    Route::get('/agent/incidents/{incident}/details', [AgentController::class, 'showIncidentDetails'])->name('agent.incident.details');
    Route::post('/agent/incidents/{incident}/message', [AgentController::class, 'sendMessage'])->name('agent.send.message');
    Route::post('/agent/incidents/{incident}/update-status', [AgentController::class, 'updateStatus'])->name('agent.update.status'); // Utilisation de PATCH pour la mise à jour du statut

    Route::get('/historique/agent', [App\Http\Controllers\HistoriqueController::class, 'agentHistorique'])->name('historique.agent');

  

    });


    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::get('/user/conversation', [App\Http\Controllers\UserController::class, 'showconversation'])
            ->name('user.conversation');
        Route::post('/user/send-message', [App\Http\Controllers\UserController::class, 'store'])->name('message.store');
        Route::post('/incidents/{incident}/messages', [MessageController::class, 'store'])->name('message.store');
        //Route::get('/incidents/{incident}/messages', [MessageController::class, 'store'])->name('message.store');

        Route::get('user/dashboard',[App\Http\Controllers\UserController::class,'index'])->name('user.dashboard');

        //pour la notation
        Route::get('/incidents/{incident}/evaluate', [RatingController::class, 'showEvaluationForm'])->name('incidents.evaluate');
        Route::post('/incidents/{incident}/evaluate', [RatingController::class, 'submitEvaluation'])->name('incidents.submitEvaluation');

        Route::get('/historique/user', [App\Http\Controllers\HistoriqueController::class, 'userHistorique'])->name('historique.user');

    });
});


Route::get('/historique/visiteur', [HistoriqueController::class, 'visiteurHistorique'])->name('historique.visiteur');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
Auth::routes();

