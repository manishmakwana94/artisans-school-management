<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\GuardianController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Teacher\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Teacher\GuardianController as TeacherGuardianController;
use App\Http\Controllers\Teacher\HomeController as TeacherHomeController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect users who try to access /register
Route::get('/register', function () {
    return redirect()->route('login');  // Redirect to login page
});

// Now register other routes (including login, logout, etc.)
Auth::routes([
    'register' => false,  // Disable registration
]);
// Auth::routes();  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('home');
    Route::get('/admin/teachers/get-details', [TeacherController::class, 'getTeacherDetails'])->name('teachers.get-details');
    Route::resource('admin/teachers', TeacherController::class);

    Route::get('admin/guardians/get-details', [GuardianController::class, 'getGuardianDetails'])->name('guardians.get-details');
    Route::resource('admin/guardians', GuardianController::class);

    Route::get('admin/students/get-details', [StudentController::class, 'getStudentsDetails'])->name('students.get-details');
    Route::resource('admin/students', StudentController::class);

    Route::get('admin/announcements/get-details', [AnnouncementController::class, 'getAnnouncementsDetails'])->name('announcements.get-details');
    Route::resource('admin/announcements', AnnouncementController::class);
});

/*------------------------------------------
--------------------------------------------
All Teacher Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'teacher'])->name('teacher.')->group(function () {

    Route::get('/teacher/home', [TeacherHomeController::class, 'index'])->name('home');

    Route::get('teachers/students/get-details', [TeacherStudentController::class, 'getStudentsDetails'])->name('students.get-details');
    Route::resource('teachers/students', TeacherStudentController::class);

    Route::get('teachers/guardians/get-details', [TeacherGuardianController::class, 'getGuardianDetails'])->name('guardians.get-details');
    Route::resource('teachers/guardians', TeacherGuardianController::class);

    Route::get('teachers/announcements/get-admin-details', [TeacherAnnouncementController::class, 'getAdminAnnouncementsDetails'])->name('announcements.get-admin-details');
    Route::get('teachers/announcements/get-details', [TeacherAnnouncementController::class, 'getAnnouncementsDetails'])->name('announcements.get-details');
    Route::resource('teachers/announcements', TeacherAnnouncementController::class);
});
