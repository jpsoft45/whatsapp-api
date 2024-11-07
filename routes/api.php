<?

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatroomController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Chatroom routes
    Route::post('/chatrooms', [ChatroomController::class, 'store']);
    Route::get('/chatrooms', [ChatroomController::class, 'index']);
    Route::post('/chatrooms/{id}/enter', [ChatroomController::class, 'enter']);
    Route::post('/chatrooms/{id}/leave', [ChatroomController::class, 'leave']);

    // Message routes
    Route::post('/chatrooms/{id}/messages', [MessageController::class, 'store']);
    Route::get('/chatrooms/{id}/messages', [MessageController::class, 'index']);
});
