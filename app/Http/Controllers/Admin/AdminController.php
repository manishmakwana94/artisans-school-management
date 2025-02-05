<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $announcements = Announcement::all();
        $students = Student::withTrashed()->get();
        $guardians = Guardian::withTrashed()->get();
        return view('admin.dashboard', compact('announcements', 'students', 'guardians'));
    }
}
