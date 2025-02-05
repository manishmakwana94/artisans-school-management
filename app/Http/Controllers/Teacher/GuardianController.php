<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\SweetAlertHelper;
use App\Http\Controllers\Controller;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     */
    // List Guardian (for DataTables)
    public function index(Request $request)
    {
        return view('teacher.guardian.index');
    }

    public function getGuardianDetails(Request $request)
    {
        if ($request->ajax()) {
            $guardian = Guardian::where('teacher_id', Auth::id())->select(['id', 'name', 'email',  'created_at']);
            return datatables()->of($guardian)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('teacher.guardians.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                            <button data-id="' . $row->id . '" onclick="deleteGuardian(' . $row->id . ')" class="btn btn-sm btn-danger delete-teacher user-' . $row->id . '">Delete</button>
                            ';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    // Show Create Guardian Form
    public function create()
    {
        $guardians = Guardian::where('teacher_id', Auth::id())->get();
        return view('teacher.guardian.create', compact('guardians'));
    }

    // Store New Guardian
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guardians,email',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
        ]);

        Guardian::create([
            'name' => $request->name,
            'email' => $request->email,
            'teacher_id' => Auth::id(),
        ]);

        SweetAlertHelper::showToast('success', 'Guardian created successfully!', 'Success');
        return redirect()->route('teacher.guardians.index');
    }

    // Show Edit Guardian Form
    public function edit($id)
    {
        $guardian = Guardian::where('id', $id)->first();
        return view('teacher.guardian.edit', compact('guardian'));
    }

    // Update Guardian
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guardians,email,' . $id,
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
        ]);

        $guardian = Guardian::findOrFail($id);
        $guardian->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        SweetAlertHelper::showToast('success', 'Guardian updated successfully!', 'Success');
        return redirect()->route('teacher.guardians.index');
    }

    // Delete Guardian
    public function destroy($id)
    {
        $guardian = Guardian::findOrFail($id);
        $guardian->delete();
        return response()->json(['success' => true], 200);
    }
}
