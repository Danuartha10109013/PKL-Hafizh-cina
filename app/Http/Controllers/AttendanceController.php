<?php

namespace App\Http\Controllers;

use App\Mail\AttendanceReminder;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\ScheduleDayM;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function kehadiran()
    {
        $user = Auth::user();
        $userid = $user->id;

        // Use optional() to handle null values
        $schedule = optional(Schedule::find($user->schedule));
        $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();

        // Retrieve all attendances for display
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', '2'); // Assuming '2' is the role ID for 'pegawai'
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($attendance) {
                // Replace null values with "N/A"
                $attendance->date = $attendance->date ?? 'N/A';
                $attendance->time = $attendance->time ?? 'N/A';
                $attendance->user_name = optional($attendance->user)->name ?? 'N/A';
                return $attendance;
            });

        // If $jadwal is empty, set it to a default value (e.g., empty collection or 'N/A')
        $jadwal = $jadwal->isEmpty() ? collect(['N/A']) : $jadwal;

        return view('pages.admin.attendance.kelolakehadiranpegawai', compact('attendances', 'jadwal'));
    }




    public function filterKehadiran(Request $request)
    {
        $user = User::where('role', '!=', 1)->pluck('id')->toArray();
        $userid = Auth::user()->id;
        $user = User::find($userid);
        $schedule = Schedule::find($user->schedule);
        // $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();


        // Ambil parameter filter tanggal dari request dan konversi formatnya
        $date = $request->input('date');
        if ($date) {
            // Konversi format tanggal dari 'dd M yyyy' ke 'Y-m-d'
            $date = Carbon::createFromFormat('d M Y', $date)->format('Y-m-d');
        }

        // Query untuk filter kehadiran berdasarkan tanggal saja
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', '2'); // Asumsi '2' adalah role untuk 'pegawai'
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return view('pages.admin.attendance.kelolakehadiranpegawai', compact('attendances'));
    }





    public function rekap()
    {
        // $data = Attendance::select('enhancer')->distinct()->get();

        // $calon = User::where('available', 10)->get();
        // $userAbsenceCounts = [];

        // foreach ($calon as $c) {
        //     $id = $c->id;

        //     // Get attendance records for this user
        //     $seleksi = Attendance::where('enhancer', $id)
        //         ->where('status', 0)
        //         ->where('time', '<=', Carbon::parse('08:00:00')->toDateTimeString())
        //         ->get();

        //     // Count absences for this user
        //     $absenceCount = $seleksi->count();

        //     // Store the count in the array
        //     $userAbsenceCounts[$id] = $absenceCount;
        // }

        // // Sort the array by the absence count in descending order
        // arsort($userAbsenceCounts);

        // // Get the top 3 users (keys will be the user IDs)
        // $topUsers = array_slice($userAbsenceCounts, 0, 3, true);

        // // Initialize variables for the top users
        // $topUser = null;
        // $secondUser = null;
        // $thirdUser = null;

        // if (isset($topUsers[key($topUsers)])) {
        //     $topUserId = key($topUsers);
        //     $topUser = User::find($topUserId);
        // }

        // if (isset($topUsers[key(array_slice($topUsers, 1, 1, true))])) {
        //     $secondUserId = key(array_slice($topUsers, 1, 1, true));
        //     $secondUser = User::find($secondUserId);
        // }

        // if (isset($topUsers[key(array_slice($topUsers, 2, 1, true))])) {
        //     $thirdUserId = key(array_slice($topUsers, 2, 1, true));
        //     $thirdUser = User::find($thirdUserId);
        // }

        // $lateAttendees = Attendance::where('status', 0)
        //     ->where('time', '>', Carbon::parse('08:00:00')->toDateTimeString())
        //     ->get()
        //     ->groupBy('user_id') // Group by user_id to count late occurrences
        //     ->filter(function ($attendances) {
        //         return $attendances->count() > 3; // Filter users who have been late more than 3 times
        //     });

        // $usersWithLateCount = $lateAttendees->map(function ($attendances) {
        //     return [
        //         'user_id' => $attendances->first()->enhancer,
        //         'late_count' => $attendances->count(), // Count how many times the user was late
        //     ];
        // });

        // // dd($usersWithLateCount);

        // // Pass data to the view
        // return view('pages.admin.attendance.rekapitulasi', compact('data', 'topUser', 'secondUser', 'thirdUser', 'usersWithLateCount'));

        // Ambil semua data pegawai
        $calon = User::where('role', '!=', 1)->get(); // Sesuaikan atribut untuk mengecualikan admin

        // Inisialisasi data absensi pegawai
        $userAbsenceCounts = [];
        foreach ($calon as $c) {
            $id = $c->id;

            // Ambil data absensi masuk sebelum jam 08:00
            $seleksi = Attendance::where('enhancer', $id)
                ->where('status', 0)
                ->where('time', '<=', Carbon::parse('08:00:00')->toDateTimeString())
                ->get();

            // Hitung jumlah absensi
            $absenceCount = $seleksi->count();

            // Simpan jumlah absensi ke array
            $userAbsenceCounts[$id] = $absenceCount;
        }

        // Sorting berdasarkan absensi terbanyak (desc)
        arsort($userAbsenceCounts);

        // Ambil top 3 pengguna dengan absensi terbanyak
        $topUsers = array_slice($userAbsenceCounts, 0, 3, true);

        $topUser = isset($topUsers[array_key_first($topUsers)]) ? User::find(array_key_first($topUsers)) : null;
        $secondUser = isset(array_slice($topUsers, 1, 1, true)[array_key_first(array_slice($topUsers, 1, 1, true))]) ? User::find(array_key_first(array_slice($topUsers, 1, 1, true))) : null;
        $thirdUser = isset(array_slice($topUsers, 2, 1, true)[array_key_first(array_slice($topUsers, 2, 1, true))]) ? User::find(array_key_first(array_slice($topUsers, 2, 1, true))) : null;


        $usersWithLateCount = Attendance::where('status', 0)
            ->whereRaw("TIME(time) > ?", ['08:00:00']) // Membandingkan hanya waktu (jam:menit:detik) dari kolom `time`
            ->get()
            ->groupBy('enhancer') // Kelompokkan berdasarkan kolom `enhancer`
            ->flatMap(function ($attendancesByEnhancer) {
                return $attendancesByEnhancer->groupBy('user_id') // Kelompokkan kembali berdasarkan `user_id`
                    ->filter(function ($userAttendances) {
                        return $userAttendances->count() > 3; // Hanya yang keterlambatannya lebih dari 3
                    })
                    ->map(function ($userAttendances) {
                        return [
                            'user_id' => $userAttendances->first()->enhancer,
                            // 'user_id' => $userAttendances->first()->user_id,
                            'late_count' => $userAttendances->count(),
                        ];
                    });
            })
            ->values(); // Reset indeks array


        // return $usersWithLateCount;

        // dd($usersWithLateCount);
        return view('pages.admin.attendance.rekapitulasi', compact('calon', 'topUser', 'secondUser', 'thirdUser', 'usersWithLateCount'));
    }


    public function cetakrekap()
    {
        // Mengambil semua pengguna aktif
        $users = User::where('role', '!=', 1)->get();
        // Membuat data rekapitulasi
        $rekapData = $users->map(function ($user) {
            // Mengambil absensi user berdasarkan enhancer (id user)
            $attendances = Attendance::where('enhancer', $user->id)->get();

            // Menghitung kehadiran dan kondisi
            $masuk = $attendances->where('status', 0)->count(); // Kehadiran masuk
            $pulang = $attendances->where('status', 1)->count(); // Kehadiran pulang

            // Terlambat jika lebih dari jam 08:00:00
            $terlambat = $attendances->where('status', 1)->filter(function ($attendance) {
                return $attendance->time > '08:00:00';
            })->count();

            // Pulang lebih awal jika kurang dari jam 17:00:00
            $lebihAwal = $attendances->where('status', 2)->filter(function ($attendance) {
                return $attendance->time < '17:00:00';
            })->count();


            // Menghitung jumlah cuti
            $cuti = Leave::where('enhancer', $user->id)->count();

            // Menentukan sanksi
            $sanksi = $terlambat > 3 ? 'danger' : ($lebihAwal > 1 ? 'warning' : 'success');
            $sanksiLabel = $terlambat > 3 ? 'Sanksi' : ($lebihAwal > 1 ? 'Perlu Perhatian' : 'Aman');

            return [
                'nama' => $user->name,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'lebih_awal' => $lebihAwal,
                'terlambat' => $terlambat,
                'cuti' => $cuti,
                'sanksi' => $sanksi,
                'sanksi_label' => $sanksiLabel,
            ];
        });

        // Mengarahkan ke tampilan cetak rekapitulasi
        return view('pages.admin.attendance.cetakrekapitulasi', compact('rekapData'));
    }

    public function cetakrekapbulan(Request $request)
{
    // Get the selected month and year from the request
    $month = $request->input('month');
    $year = $request->input('year');
    
    // Filter users excluding role 1 (admin)
    $users = User::where('role', '!=', 1)->get();

    // Create rekapitulasi data based on month and year filters
    $rekapData = $users->map(function ($user) use ($month, $year) {
        // Filter attendance by user and month/year
        $attendances = Attendance::where('enhancer', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        // Count attendance statuses
        $masuk = $attendances->where('status', 0)->count(); // Kehadiran masuk
        $pulang = $attendances->where('status', 1)->count(); // Kehadiran pulang

        // Filter for terlambat if time is after 08:00:00
        $terlambat = $attendances->where('status', 1)->filter(function ($attendance) {
            return $attendance->time > '08:00:00';
        })->count();

        // Filter for lebih awal if time is before 17:00:00
        $lebihAwal = $attendances->where('status', 2)->filter(function ($attendance) {
            return $attendance->time < '17:00:00';
        })->count();

        // Filter leave records by user and month/year
        $cuti = Leave::where('enhancer', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Determine sanction based on conditions
        $sanksi = $terlambat > 3 ? 'danger' : ($lebihAwal > 1 ? 'warning' : 'success');
        $sanksiLabel = $terlambat > 3 ? 'Sanksi' : ($lebihAwal > 1 ? 'Perlu Perhatian' : 'Aman');

        return [
            'nama' => $user->name,
            'masuk' => $masuk,
            'pulang' => $pulang,
            'lebih_awal' => $lebihAwal,
            'terlambat' => $terlambat,
            'cuti' => $cuti,
            'sanksi' => $sanksi,
            'sanksi_label' => $sanksiLabel,
        ];
    });

    // Return the rekapitulasi view with filtered data
    return view('pages.admin.attendance.cetakrekapitulasi', compact('rekapData', 'month', 'year'));
}


    public function filtertanggal() {}


    public function cetakKehadiran(Request $request)
    {
        // Validasi input
        $request->validate([
            'date' => 'nullable|string', // Inputnya dalam format 'dd M yyyy'
            'month' => 'nullable|date_format:Y-m',
            'year' => 'nullable|numeric|min:1900|max:' . now()->year,
        ]);

        // Pilihan cetak
        $printOption = $request->input('printOption');
        $query = Attendance::with('user')->whereHas('user', function ($query) {
            $query->where('role', '2'); // Assuming '2' is the role ID for 'pegawai'
        });

        // Apply filter berdasarkan print option
        if ($printOption == 'byDate' && $request->has('date')) {
            // Konversi tanggal dari 'dd M yyyy' ke 'Y-m-d'
            $date = Carbon::createFromFormat('d M Y', $request->input('date'))->format('Y-m-d');
            $query->whereDate('date', $date);
        } elseif ($printOption == 'byMonth' && $request->has('month')) {
            $month = $request->input('month');
            $query->whereMonth('date', date('m', strtotime($month)))
                ->whereYear('date', date('Y', strtotime($month)));
        } elseif ($printOption == 'byYear' && $request->has('year')) {
            $query->whereYear('date', $request->input('year'));
        }

        // Ambil data kehadiran
        $attendances = $query->orderBy('date', 'asc')->orderBy('time', 'asc')->get();

        // Debugging jika data kosong
        if ($attendances->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada data untuk filter yang dipilih.']);
        }

        // Return view dengan data
        return view('pages.admin.attendance.printkehadiranpegawai', compact('attendances'));
    }




    // Tampilkan daftar kehadiran
    // public function index()
    // {

    //     $userid = Auth::user()->id;
    //     $user = User::find($userid);
    //     $schedule = Schedule::find($user->schedule);
    //     $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();
    //     // dd($jadwal);

    //     $id = Auth::user()->id;
    //     // dd($id);
    //     $orang = Auth::user()->schedule;
    //     $jadwal = Schedule::where('id', $orang)->value('id');
    //     $jadwal_detail = ScheduleDayM::where('schedule_id', $jadwal)->get();
    //     $attendances = Attendance::where('enhancer', $id)->get();
    //     return view('pages.pegawai.attendance.index', compact('attendances', 'jadwal', 'jadwal_detail'));
    // }
    public function index()
    {
        $userid = Auth::user()->id;
        $user = User::find($userid);

        // Tambahan cek jika user belum memiliki jadwal
        $schedule = $user->schedule ? Schedule::find($user->schedule) : null;
        $jadwal = $schedule ? ScheduleDayM::where('schedule_id', $schedule->id)->get() : collect();

        $id = Auth::user()->id;
        $orang = Auth::user()->schedule;

        // Pastikan jadwal ada atau gunakan nilai default
        $jadwal = $orang ? Schedule::where('id', $orang)->value('id') : null;
        $jadwal_detail = $jadwal ? ScheduleDayM::where('schedule_id', $jadwal)->get() : collect();

        $attendances = Attendance::where('enhancer', $id)->get();

        return view('pages.pegawai.attendance.index', compact('attendances', 'jadwal', 'jadwal_detail'));
    }

    public function setup(Request $request){
        $user = User::find($request->id_user);
        // dd($request->all());
        if ($request->hasFile('acuan')) {
            $acuanPath = $request->file('acuan')->store('acuan', 'public');
            $user->acuan = $acuanPath;
            $user->update();
            return redirect()->back()->with('Acuan berhasil disimpan');
        }else{
            return redirect()->back()->withErrors('Terjadi kesalahan');
        }
    }


    // Tampilkan halaman presensi
    public function create()
    {
        return view('pages.pegawai.attendance.create');
    }

    // Simpan data presensi
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:0,1',
            'coordinate' => 'required',
            'faceData' => 'required|string',
        ]);
    
        // Decode Base64 Image
        $base64Image = $request->input('faceData');
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            return back()->with('error', 'Format gambar tidak valid.');
        }
    
        $extension = $matches[1];
        $base64Image = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
    
        if ($base64Image === false) {
            return back()->with('error', 'Gambar tidak valid.');
        }
    
        // Simpan gambar absensi
        $fileName = Str::uuid() . '.' . $extension;
        $imagePath = "attendances/{$fileName}";
    
        Storage::disk('public')->put($imagePath, $base64Image);
    
        // Simpan data absensi ke database
        $attendance = Attendance::create([
            'enhancer' => Auth::id(),
            'date' => now()->format('Y-m-d'),
            'time' => now(),
            'image' => $imagePath,
            'status' => $request->input('status'),
            'coordinate' => $request->input('coordinate'),
        ]);
    
        $user = Auth::user();
    
        // Dapatkan folder acuan
        $acuanFolder = storage_path("app/public/acuan");
    
        // Periksa apakah ada gambar referensi
        $referenceImages = glob($acuanFolder . "/*.jpg"); // Bisa disesuaikan ke PNG jika perlu
        if (empty($referenceImages)) {
            $attendance->forceDelete();
            return back()->with('error', 'Tidak ada gambar referensi yang tersedia.');
        }
    
        // Jalankan Python Face Recognition
        $fullImagePath = storage_path("app/public/" . $imagePath);
        $command = escapeshellcmd("python recognition.py " . escapeshellarg($fullImagePath) . " " . escapeshellarg($acuanFolder));
    
        $output = [];
        $status_code = 0;
        exec($command, $output, $status_code);
    
        if ($status_code !== 0) {
            $attendance->forceDelete();
            return redirect()->route('pegawai.attendance')->with('error', 'Gagal menjalankan Face Recognition.');
        }
    
        // Ambil hasil dari Python
        $user_name = trim($output[0] ?? 'Unknown');
        // dd($user_name);
    
        if ($user_name === 'Unknown') {
            $attendance->forceDelete();
            return redirect()->route('pegawai.attendance')->with('error', 'Wajah tidak dikenali.');
        }
    
        // Cek apakah wajah dikenali dalam daftar pegawai
        $hasil = User::where('acuan', "acuan/{$user_name}.jpg")->first();
        // dd($hasil);
    
        if (!$hasil) {
            $attendance->forceDelete();
            return redirect()->route('pegawai.attendance')->with('error', 'User tidak ditemukan.');
        }
    
        // Pastikan yang terdeteksi adalah user yang sedang login
        if ($hasil->id !== $user->id) {
            $attendance->forceDelete();
            return redirect()->route('pegawai.attendance')->with('error', 'Anda hanya bisa absen untuk diri sendiri. Wajah terdeteksi sebagai '.$hasil->name);
        }
    
        return redirect()->route('pegawai.attendance')->with('success', 'Kehadiran berhasil disimpan. Wajah terdeteksi sebagai ' . $hasil->name);
    }
    

    


    // Cetak data kehadiran per pegawai
    public function print($id)
    {
        $attendance = Attendance::findOrFail($id);
        $id_user = Auth::user()->id;
        $name = User::where('id', $id_user)->value('name');
        // Mengolah string coordinate menjadi array
        $coordinates = explode(',', $attendance->coordinate);
        $latitude = $coordinates[0] ?? null;
        $longitude = $coordinates[1] ?? null;

        // Tampilkan tampilan print
        return view('pages.pegawai.attendance.print', compact('attendance', 'latitude', 'longitude', 'name'));
    }
    public function printcustom(Request $request)
    {
        // Validasi input form
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'year' => 'required|integer|min:1900|max:2099',
        ]);

        // Ambil bulan dan tahun dari request
        $month = date('m', strtotime($request->input('month')));
        $year = $request->input('year');

        // Ambil ID user yang sedang login
        $id_user = Auth::id();

        // Query untuk mengambil data absensi berdasarkan bulan, tahun, dan user
        $attendance = Attendance::where('enhancer', $id_user) // Ganti 'user_id' dengan 'enhancer'
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Ambil nama user
        $name = User::where('id', $id_user)->value('name');

        // Jika absensi ditemukan, proses koordinat untuk absensi pertama
        if ($attendance->isNotEmpty()) {
            $coordinates = explode(',', $attendance->first()->coordinate);
            $latitude = $coordinates[0] ?? null;
            $longitude = $coordinates[1] ?? null;
        } else {
            $latitude = null;
            $longitude = null;
        }

        // Tampilkan tampilan print dengan data absensi
        return view('pages.pegawai.attendance.printcustom', compact('attendance', 'latitude', 'longitude', 'name'));
    }





    public function cetakkehadiranmasuk($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('status', 0)
            ->firstOrFail(); // Mengambil data yang ada, atau gagal jika tidak ditemukan

        // dd($attendance); // Cek apakah data ditemukan

        $coordinates = explode(',', $attendance->coordinate);
        $latitude = $coordinates[0] ?? null;
        $longitude = $coordinates[1] ?? null;
        // dd($latitude);
        return view('pages.admin.attendance.printkehadiran-masuk', compact('attendance', 'latitude', 'longitude'));
    }


    public function cetakkehadirankeluar($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('status', 1)
            ->firstOrFail(); // Mengambil data yang ada, atau gagal jika tidak ditemukan

        // dd($attendance); // Cek apakah data ditemukan

        // Mengolah string coordinate menjadi array
        $coordinates = explode(',', $attendance->coordinate);
        $latitude = $coordinates[0] ?? null;
        $longitude = $coordinates[1] ?? null;

        // Tampilkan tampilan print
        return view('pages.admin.attendance.printkehadiran-keluar', compact('attendance', 'latitude', 'longitude'));
    }

    public function send($id)
    {
        // Find the user by their ID
        $user = User::find($id);
        // dd($user->email);

        if ($user) {
            // Send email using the Mailable class
            Mail::to($user->email)->send(new AttendanceReminder($user));

            return redirect()->back()->with('success', 'Email Telah Dikirim');
        } else {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan');
        }
    }

    public function delete($id)
    {
        // Find the schedule to be deleted
        $schedule = Attendance::findOrFail($id);
        $schedule->deleted_by = Auth::user()->id;
        $schedule->save();
        $schedule->delete();

        return redirect()->back()->with('success', 'Absensi telah dihapus');
    }


    public function restore($id)
    {

        $att = Attendance::withTrashed()->find($id);
        $att->deleted_by = null;
        $att->save();
        // Restore the specific Schedule record with the given id
        Attendance::withTrashed()->where('id', $id)->restore();

        return redirect()->back()->with('success', 'Absensi telah dipulihkan');
    }


    public function forceDelete($id)
    {
        $att = Attendance::withTrashed()->findOrFail($id);
        $att->forceDelete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Absensi telah dihapus secara permanen');
    }
}
