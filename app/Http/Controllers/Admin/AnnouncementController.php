<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SweetAlertHelper;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.announcement.index');
    }

    public function getAnnouncementsDetails(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::with(['user:id,name,role'])->select('id', 'user_id', 'target', 'content', 'created_at');

            return DataTables::of($announcements)
                ->addIndexColumn()
                ->addColumn('user_name', function ($creater) {
                    $is_you = $creater->user_id == Auth::id() ? ' (You)' : '';
                    return $creater->user ? $creater->user->name . ' ' . $is_you  : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<button data-announcement=\'' . json_encode($row) . '\' onclick="viewAnnouncement(this)" class="btn btn-sm btn-primary">View</button>  
                    <button data-id="' . $row->id . '" onclick="deleteAnnouncement(' . $row->id . ')" class="btn btn-sm btn-danger delete-announcement user-' . $row->id . '">Delete</button>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->filterColumn('user_role', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('role', 'LIKE', "%{$keyword}%");
                    });
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
        return view('admin.announcement.create');
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

        ], [
            'content.required' => 'The name field is required.'
        ]);
        // Create the user
        $user = Announcement::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        SweetAlertHelper::showToast('success', 'Announcement created successfully!', 'Success');
        return redirect()->route('admin.announcements.index');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return response()->json(['success' => true], 200);
    }
}
