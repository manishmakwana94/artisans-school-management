<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SweetAlertHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.teacher.index');
    }

    public function getTeacherDetails(Request $request)
    {
        if ($request->ajax()) {
            $teachers = User::select('id', 'name', 'email', 'created_at')->where('role', 'teacher');

            return DataTables::of($teachers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.teachers.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                            <button data-id="' . $row->id . '" onclick="deleteUser(' . $row->id . ')" class="btn btn-sm btn-danger delete-teacher user-' . $row->id . '">Delete</button>
                            ';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
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
        return view('admin.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:20',
                'regex:/[A-Z]/',        // At least one uppercase letter
                'regex:/[a-z]/',        // At least one lowercase letter
                'regex:/[0-9]/',        // At least one number
                'regex:/[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?\\|`~]/',    // At least one special character
            ],
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        SweetAlertHelper::showToast('success', 'Teacher created successfully!', 'Success');
        return redirect()->route('admin.teachers.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('admin.teacher.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $user = User::findOrFail($id);
        // return view('admin.teacher.edit', compact('user'));
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $id,
            ],
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
        ]);


        $user = User::findOrFail($id);
        $user->update($request->all());
        SweetAlertHelper::showToast('success', 'Teacher updated successfully!', 'Success');
        return redirect()->route('admin.teachers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = User::findOrFail($id);
        $teacher->delete();
        return response()->json(['success' => true], 200);
    }
}
