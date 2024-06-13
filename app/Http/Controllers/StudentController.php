<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        return view('admission_form');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'gender' => 'required',
            'age' => 'required|integer',
            'address' => 'required',
            'tc_file' => 'required|file',
            'mark_sheet_file' => 'required|file',
            'gps_coordinates' => 'nullable',
        ]);

        $tcFilePath = $request->file('tc_file')->store('tc_files');
        $markSheetFilePath = $request->file('mark_sheet_file')->store('mark_sheet_files');

        $freeBusFare = false;
        if ($request->input('gps_coordinates')) {
            $coordinates = explode(',', $request->input('gps_coordinates'));
            $latitude = $coordinates[0];
            $longitude = $coordinates[1];

            // School coordinates
            $schoolLatitude = 10.033;
            $schoolLongitude = 76.3921148;

            // Calculate free bus fare eligibility
            $distance = $this->calculateDistance($latitude, $longitude, $schoolLatitude, $schoolLongitude);
            $freeBusFare = $distance <= 2;
        }

        $student = new Student([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'tc_file_path' => $tcFilePath,
            'marksheet_file_path' => $markSheetFilePath,
            'latitude' => $latitude ?? null,
            'longitude' => $longitude ?? null,
            'free_bus_fare' => $freeBusFare,
        ]);

        $student->save();

        return response()->json(['message' => 'Application submitted successfully!'], 201);
    }

    public function status(Request $request)
    {
        if ($request->has('email')) {
            $email = $request->input('email');
            $student = Student::where('email', $email)->first();

            if ($student) {
                return view('status', ['student' => $student]);
            } else {
                return view('status')->withErrors(['email' => 'Student not found']);
            }
        }

        return view('status');
    }

    public function adminIndex()
    {
        $students = Student::all();
        return view('admin.submissions', ['students' => $students]);
    }

    public function adminAdmissions()
    {
        $students = Student::where('admitted', true)->get();
        return view('admin.admissions', ['students' => $students]);
    }

    public function updateStatus(Request $request)
{
    $studentId = $request->input('student_id');
    $admitted = $request->input('admitted') ? true : false;

    $student = Student::find($studentId);
    if ($student) {
        $student->admitted = $admitted;
        $student->save();
        return redirect()->route('admin_submissions')->with('success', 'Status updated successfully!');
    } else {
        return redirect()->route('admin_submissions')->withErrors(['message' => 'Student not found']);
    }
}


    private function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($latitudeTo - $latitudeFrom);
        $dLon = deg2rad($longitudeTo - $longitudeFrom);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}
