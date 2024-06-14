<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Address</th>
            <th>TC</th>
            <th>Mark Sheet</th>
            <th>GPS Coordinates</th>
            <th>Admitted</th>
            <th>Free Bus Fare</th>
            <th>Actions</th>
        </tr>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->gender }}</td>
            <td>{{ $student->age }}</td>
            <td>{{ $student->address }}</td>
            <td><a href="{{ Storage::url($student->tc_file) }}" target="_blank">View TC</a></td>
            <td><a href="{{ Storage::url($student->marksheet_file) }}" target="_blank">View Mark Sheet</a></td>
            <td>{{ $student->gps_coordinates }}</td>
            <td>{{ $student->admitted ? 'Yes' : 'No' }}</td>
            <td>
                @php
                    $schoolLatitude = 10.033;
                    $schoolLongitude = 76.3921148;
                    $studentCoords = explode(',', $student->gps_coordinates);
                    $studentLatitude = $studentCoords[0];
                    $studentLongitude = $studentCoords[1];

                    function haversine($lat1, $lon1, $lat2, $lon2) {
                        $earth_radius = 6371;
                        $dLat = deg2rad($lat2 - $lat1);
                        $dLon = deg2rad($lon2 - $lon1);
                        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                        return $earth_radius * $c;
                    }

                    $distance = haversine($schoolLatitude, $schoolLongitude, $studentLatitude, $studentLongitude);
                @endphp

                {{ $distance <= 2 ? 'Yes' : 'No' }}
            </td>
            <td>
                <form action="/admin/update-status" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="checkbox" name="admitted" {{ $student->admitted ? 'checked' : '' }} onchange="this.form.submit()">
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
