<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $adminAnnouncements = Announcement::where('target', 'teachers')->get();
        $myAnnouncements = FacadesAuth::user()->announcements;
        return view('teacher.dashboard', compact('adminAnnouncements', 'myAnnouncements'));
    }
}
