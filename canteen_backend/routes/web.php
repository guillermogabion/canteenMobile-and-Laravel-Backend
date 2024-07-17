<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClassSetController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomUserClasssetController;
use App\Http\Controllers\ExamBuilderController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\OrderController;
use App\Models\ClassroomUserClassset;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});


Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

    Route::get('/files', [AttendanceController::class, 'index'])->name('attendance');



    // meals 
    Route::get('/meals', [MealsController::class, 'index'])->name('meal');
    Route::post('/meal-store', [MealsController::class, 'storeMeal'])->name('meal.store');
    Route::put('/meal_update', [MealsController::class, 'update']);

    // order 

    Route::get('/orders', [OrderController::class, 'index'])->name('order');




    // warehouse 
    // Route::get('/warehouse/items', [WarehouseController::class, 'getItems']);
    // Route::post('/warehouse_add', [WarehouseController::class, 'store'])->name('warehouse-add');
    // Route::post('/warehouse_update', [WarehouseController::class, 'update'])->name('warehouse-update');
    // Route::post('/update-warehouse-quantities', [WarehouseController::class, 'updateWarehouseQuantities'])->name('update-warehouse-quantities');

    // // users 
    // Route::get('/users', [UsersController::class, 'index'])->name('users');

    // Route::post('/user_add', [UsersController::class, 'store'])->name('user-add');
    // Route::post('/user_update', [UsersController::class, 'update'])->name('user-update');
    // Route::post('/users/{id}/status', [UsersController::class, 'updateStatus'])->name('users.updateStatus');
    // // cashier 
    // Route::get('/cashier', [InventoryController::class, 'cashier'])->name('cashier');
    // Route::get('/cashier-search', [InventoryController::class, 'searchAvailable'])->name('cashier-search');

    // // payment 

    // Route::post('/payment', [PaymentController::class, 'store'])->name('payment');

    // // class 
    // Route::get('/class', [ClassSetController::class, 'index'])->name('class');
    // Route::post('/class_add', [ClassSetController::class, 'store'])->name('class-add');
    // Route::post('/class/{id}/status', [ClassSetController::class, 'updateStatus'])->name('class.updateStatus');

    // // filemanager 
    // // Route::post('/upload', [FileManagerController::class, 'store'])->name('upload');
    // // Route::delete('/file/{id}', [FileManagerController::class, 'destroy'])->name('file.destroy');

    // // classrooms 

    // Route::get('/room', [ClassroomController::class, 'index'])->name('room');
    // Route::post('/room_add', [ClassroomController::class, 'store'])->name('room-add');
    // Route::post('/room_edit', [ClassroomController::class, 'update'])->name('room-edit');
    // Route::post('/room_delete', [ClassroomController::class, 'delete'])->name('room_delete');
    // Route::post('/room/{id}/status', [ClassroomController::class, 'updateStatus'])->name('room.updateStatus');
    // Route::get('/room/{id}', [ClassroomController::class, 'show'])->name('pages.room.show');


    // // exam 
    // Route::get('/exam', [ExamBuilderController::class, 'index'])->name('exam');
    // Route::post('/exam_add', [ExamBuilderController::class, 'store'])->name('exam-add');
    // Route::post('/exam_edit', [ExamBuilderController::class, 'update'])->name('exam-edit');
    // Route::post('/exam_delete', [ExamBuilderController::class, 'delete'])->name('exam_delete');
    // Route::post('/exam/{id}/status', [ExamBuilderController::class, 'updateStatus'])->name('exam.updateStatus');


    // Route::post('/user_class/add', [ClassroomUserClasssetController::class, 'store'])->name('user_class_add');


});
Route::get('/send-test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('gabionian7@gmail.com')
            ->subject('Test Email');
    });

    return 'Test email sent';
});
