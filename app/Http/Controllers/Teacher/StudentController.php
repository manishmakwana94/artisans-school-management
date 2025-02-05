<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\SweetAlertHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher'); // Restrict to teachers
    }

    // List Students (for DataTables)
    public function index(Request $request)
    {
        return view('teacher.student.index');
    }

    public function getStudentsDetails(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::where('students.teacher_id', Auth::id())
                ->with('guardian')
                ->select([
                    'students.id', // Rename id to avoid ambiguity
                    'students.name',
                    'students.email',
                    'students.guardian_id',
                    'students.created_at'
                ]);

            return datatables()->of($students)
                ->addIndexColumn()
                ->addColumn('guardian', function ($student) {
                    return $student->guardian ? $student->guardian->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('teacher.students.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                        <button data-id="' . $row->id . '" onclick="deleteStudent(' . $row->id . ')" class="btn btn-sm btn-danger delete-teacher user-' . $row->student_id . '">Delete</button>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    // Show Create Student Form
    public function create()
    {
        $guardians = Guardian::where('teacher_id', Auth::id())->get();
        return view('teacher.student.create', compact('guardians'));
    }

    // Store New Student
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email',
            'guardian_id' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'guardian_id' => 'The password must be at least 6 characters long.',
        ]);

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'guardian_id' => $request->guardian_id,
            'teacher_id' => Auth::id(),
        ]);
        SweetAlertHelper::showToast('success', 'Student created successfully!', 'Success');
        return redirect()->route('teacher.students.index');
    }

    // Show Edit Student Form
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $guardians = Guardian::where('teacher_id', Auth::id())->get();
        return view('teacher.student.edit', compact('student', 'guardians'));
    }

    // Update Student
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            'guardian_id' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'guardian_id' => 'The password must be at least 6 characters long.',
        ]);

        $student = Student::findOrFail($id);
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'guardian_id' => $request->guardian_id,
        ]);
        SweetAlertHelper::showToast('success', 'Student updated successfully!', 'Success');
        return redirect()->route('teacher.students.index');
    }

    // Delete Student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(['success' => true], 200);
        // return redirect()->route('teacher.students.index')->with('success', 'Student deleted successfully.');
    }
}
