<?php

// StudentController.php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Welcome to the Admission Portal'], 200);
        }
        return view('welcome');
    }

    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Admission form endpoint'], 200);
        }
        return view('admission_form');
    }

    public function store(Request $request)
{
    try {
        Log::info('Store request received', ['request' => $request->all()]);

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'gender' => 'required',
            'age' => 'required|integer',
            'address' => 'required',
            'tc_file' => 'required|file',
            'marksheet_file' => 'required|file',
            'gps_coordinates' => 'nullable',
        ]);

        Log::info('Validation passed', ['validatedData' => $validatedData]);

        $tcFilePath = $request->file('tc_file')->store('tc_files', 'public');
        $markSheetFilePath = $request->file('marksheet_file')->store('marksheet_files', 'public');

        Log::info('Files uploaded', [
            'tc_file' => $tcFilePath,
            'marksheet_file' => $markSheetFilePath,
        ]);

        $freeBusFare = false;
        if ($request->input('gps_coordinates')) {
            $coordinates = explode(',', $request->input('gps_coordinates'));
            $latitude = $coordinates[0];
            $longitude = $coordinates[1];

            // School coordinates
            $schoolLatitude = 10.0143499;
            $schoolLongitude = 76.3921148;

            // Calculate free bus fare eligibility
            $distance = $this->calculateDistance($latitude, $longitude, $schoolLatitude, $schoolLongitude);
            $freeBusFare = $distance <= 2;

            Log::info('Distance calculated', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'distance' => $distance,
                'freeBusFare' => $freeBusFare,
            ]);
        }

        $student = new Student([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'tc_file' => $tcFilePath,
            'marksheet_file' => $markSheetFilePath,
            'gps_coordinates' => $request->input('gps_coordinates'),
            'free_bus_fare' => $freeBusFare,
        ]);

        $student->save();

        Log::info('Student saved', ['student' => $student]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Application submitted successfully!'], 201);
        }

        return redirect('/')->with('success', 'Application submitted successfully!');
    } catch (\Exception $e) {
        Log::error('Error storing student application: ' . $e->getMessage());
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Failed to submit application.'], 500);
        }
        return redirect()->back()->with('error', 'Failed to submit application.');
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

    public function status(Request $request)
    {
        try {
            if ($request->has('email')) {
                $email = $request->input('email');
                $student = Student::where('email', $email)->first();

                if ($student) {
                    if ($request->expectsJson()) {
                        return response()->json(['student' => $student], 200);
                    }
                    return view('status', ['student' => $student]);
                } else {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Student not found'], 404);
                    }
                    return view('status')->withErrors(['email' => 'Student not found']);
                }
            }

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Email parameter is required'], 400);
            }
            return view('status');
        } catch (\Exception $e) {
            Log::error('Error fetching student status: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to fetch status.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to fetch status.');
        }
    }

    public function adminIndex(Request $request)
    {
        try {
            $students = Student::all();
            if ($request->expectsJson()) {
                return response()->json(['students' => $students], 200);
            }
            return view('admin.submissions', ['students' => $students]);
        } catch (\Exception $e) {
            Log::error('Error fetching student submissions: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to fetch submissions.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to fetch submissions.');
        }
    }

    public function adminAdmissions(Request $request)
    {
        try {
            $students = Student::where('admitted', true)->get();
            if ($request->expectsJson()) {
                return response()->json(['students' => $students], 200);
            }
            return view('admin.admissions', ['students' => $students]);
        } catch (\Exception $e) {
            Log::error('Error fetching admitted students: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to fetch admissions.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to fetch admissions.');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'admitted' => 'required|boolean',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }

            $student = Student::find($request->input('student_id'));
            if ($student) {
                $student->admitted = $request->input('admitted');
                $student->save();
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Status updated successfully!'], 200);
                }
                return redirect()->route('admin_submissions')->with('success', 'Status updated successfully!');
            } else {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Student not found'], 404);
                }
                return redirect()->route('admin_submissions')->withErrors(['message' => 'Student not found']);
            }
        } catch (\Exception $e) {
            Log::error('Error updating student status: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to update status.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }
}
