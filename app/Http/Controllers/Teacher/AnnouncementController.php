<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\SweetAlertHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SendAnnouncementEmailJob;
use App\Models\Announcement;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('teacher.announcement.index');
    }
    public function getAdminAnnouncementsDetails(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::with(['user:id,name,role'])
                ->whereHas('user', function ($query) {
                    $query->where('role', 'admin');
                })
                ->select('id', 'user_id', 'target', 'content', 'created_at');

            return DataTables::of($announcements)
                ->addIndexColumn()
                ->addColumn('user_name', function ($creater) {
                    $is_you = $creater->user_id == Auth::id() ? ' (You)' : '';
                    return $creater->user ? $creater->user->name . ' ' . $is_you  : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<button data-announcement=\'' . json_encode($row) . '\' onclick="viewAnnouncement(this)" class="btn btn-sm btn-primary">View</button>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getAnnouncementsDetails(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::with(['user:id,name,role'])
                ->where('user_id', Auth::id())->select('id', 'user_id', 'target', 'content', 'created_at');

            return DataTables::of($announcements)
                ->addIndexColumn()
                ->addColumn('user_name', function ($creater) {
                    $is_you = $creater->user_id == Auth::id() ? ' (You)' : '';
                    return $creater->user ? $creater->user->name . ' ' . $is_you  : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<button data-announcement=\'' . json_encode($row) . '\' onclick="viewAnnouncement(this)" class="btn btn-sm btn-primary">View</button>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teacher.announcement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'content' => [
                'required'
            ],
            'target' => [
                'required'
            ]

        ], [
            'content.required' => 'The name field is required.',
            'target.required' => 'The target field is required.'
        ]);

        $announcement = Announcement::create([
            'content' => $request->content,
            'target' => $request->target,
            'user_id' => Auth::id(),
        ]);

        $recipients = collect();
        if ($announcement->target === 'guardians' || $announcement->target === 'both') {
            $recipients = $recipients->merge(Guardian::select('name', 'email')->get());
        }
        if ($announcement->target === 'students' || $announcement->target === 'both') {
            $recipients = $recipients->merge(Student::select('name', 'email')->get());
        }

        $announcementData = [
            'content' => $announcement->content,
            'subject' => 'Important School Announcement'
        ];
        dispatch(new SendAnnouncementEmailJob($announcementData, $recipients, Auth::user()->name));


        SweetAlertHelper::showToast('success', 'Announcement created successfully!', 'Success');
        return redirect()->route('teacher.announcements.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}
}
