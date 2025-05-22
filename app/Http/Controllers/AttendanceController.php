<?php

namespace App\Http\Controllers;

use App\Mail\AttendanceReminder;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\PeringatanM;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\ScheduleDayM;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
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





    public function rekap(Request $request)
    {
        // Ambil semua pegawai kecuali admin
        $calon = User::where('role', '!=', 1)->get();

        $userLateCounts = []; // Untuk menyimpan jumlah keterlambatan tiap user

        foreach ($calon as $user) {
            if (!$user->schedule) {
                $userLateCounts[$user->id] = 0;
                continue;
            }

            // Ambil jadwal hari kerja user
            $scheduleDays = ScheduleDayM::where('schedule_id', $user->schedule)->get();
            if ($scheduleDays->isEmpty()) {
                $userLateCounts[$user->id] = 0;
                continue;
            }

            $dayNames = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu'
            ];

            // Cari tanggal pertama user absen (datang) agar hitungannya mulai dari sini
            $firstAttendance = Attendance::where('enhancer', $user->id)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$firstAttendance) {
                $userLateCounts[$user->id] = 0; // Tidak ada absen sama sekali
                continue;
            }

            $startDate = Carbon::parse($firstAttendance->created_at)->startOfDay();
            $today = Carbon::now()->startOfDay();

            $lateCount = 0;

            // Ambil semua absensi user mulai dari firstAttendance sampai hari ini, grouped by tanggal
            $attendances = Attendance::where('enhancer', $user->id)
                ->whereBetween('created_at', [$startDate, $today])
                ->get()
                ->groupBy(fn($a) => Carbon::parse($a->created_at)->format('Y-m-d'));

            // Loop dari tanggal pertama absen sampai hari ini
            for ($date = $startDate->copy(); $date <= $today; $date->addDay()) {
                $dayName = $dayNames[$date->dayOfWeek];
                $matchedDay = $scheduleDays->firstWhere('days', $dayName);

                if (!$matchedDay) {
                    // Hari bukan hari kerja user, skip
                    continue;
                }

                $scheduledClockIn = $matchedDay->clock_in; // Jam masuk dari jadwal user (format 'H:i:s')

                $dateStr = $date->format('Y-m-d');
                $attendance = $attendances->get($dateStr);

                if ($attendance && $attendance->count() > 0) {
                    // Ambil jam absen pertama (clock in)
                    $actualTime = Carbon::parse($attendance->first()->time)->format('H:i:s');

                    if ($actualTime > $scheduledClockIn) {
                        $lateCount++;
                    }
                }
                // Jika tidak absen sama sekali, tidak dihitung untuk top keterlambatan
            }

            $userLateCounts[$user->id] = $lateCount;
        }

        // Urutkan dari keterlambatan terbanyak desc
        arsort($userLateCounts);

        // Ambil top 3 user dengan keterlambatan terbanyak
        $topUsers = array_slice($userLateCounts, 0, 3, true);

        $topUser = isset($topUsers[array_key_first($topUsers)]) ? User::find(array_key_first($topUsers)) : null;
        $secondUser = isset(array_slice($topUsers, 1, 1, true)[array_key_first(array_slice($topUsers, 1, 1, true))]) ? User::find(array_key_first(array_slice($topUsers, 1, 1, true))) : null;
        $thirdUser = isset(array_slice($topUsers, 2, 1, true)[array_key_first(array_slice($topUsers, 2, 1, true))]) ? User::find(array_key_first(array_slice($topUsers, 2, 1, true))) : null;

        // Sekarang buat hasil rekap untuk semua user (late + absent), mulai dari tanggal pertama absen user

        $result = collect();

        $now = Carbon::now();
        // $today = $now->copy()->startOfDay();
        $today = $now->copy()->startOfDay()->addDay();

        foreach ($calon as $user) {
            if (!$user->schedule) {
                continue;
            }

            $scheduleDays = ScheduleDayM::where('schedule_id', $user->schedule)->get();
            if ($scheduleDays->isEmpty()) {
                continue;
            }

            $dayNames = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu'
            ];

            $firstAttendance = Attendance::where('enhancer', $user->id)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$firstAttendance) {
                continue; // Kalau belum pernah absen, skip
            }

            $startDate = Carbon::parse($firstAttendance->created_at)->startOfDay();

            $workdays = collect();
            for ($date = $startDate->copy(); $date <= $today; $date->addDay()) {
                $dayName = $dayNames[$date->dayOfWeek];
                $matchedDay = $scheduleDays->firstWhere('days', $dayName);

                if ($matchedDay) {
                    $workdays->push([
                        'date' => $date->format('Y-m-d'),
                        'clock_in' => $matchedDay->clock_in
                    ]);
                }
            }

            $attendances = Attendance::where('enhancer', $user->id)
                ->whereBetween('created_at', [$startDate, $today])
                ->get()
                ->groupBy(fn($a) => Carbon::parse($a->created_at)->format('Y-m-d'));
            // dd($attendances);
            $lateCount = 0;
            $absentDates = [];

            foreach ($workdays as $workday) {
                $date = $workday['date'];
                $scheduledClockIn = $workday['clock_in'];
                $attendance = $attendances->get($date);

                if ($attendance && $attendance->count() > 0) {
                    $actualTime = Carbon::parse($attendance->first()->time)->format('H:i:s');
                    if ($actualTime > $scheduledClockIn) {
                        $lateCount++;
                    }
                } else {
                    $absentDates[] = $date;
                }
            }

            if ($lateCount > 0 || count($absentDates) > 0) {
                $result->push([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'late_count' => $lateCount,
                    'absent_count' => count($absentDates) - 1,
                    'missing_dates' => $absentDates,
                ]);
            }
        }

        return view('pages.admin.attendance.rekapitulasi', compact('calon', 'topUser', 'secondUser', 'thirdUser', 'result', 'request'));
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

    public function setup(Request $request)
    {
        $user = User::find($request->id_user);
        // dd($request->all());
        if ($request->hasFile('acuan')) {
            $acuanPath = $request->file('acuan')->store('acuan', 'public');
            $user->acuan = $acuanPath;
            $user->update();
            return redirect()->back()->with('Acuan berhasil disimpan');
        } else {
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
        // dd($request->all());
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
            return redirect()->route('pegawai.attendance')->with('error', 'Anda hanya bisa absen untuk diri sendiri. Wajah terdeteksi sebagai ' . $hasil->name);
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
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'year' => 'required|integer|min:1900|max:2099',
        ]);

        $month = date('m', strtotime($request->input('month')));
        $year = $request->input('year');
        $id_user = Auth::id();

        $user = Auth::user();
        $name = $user->name;
        $scheduleId = $user->schedule;

        // Ambil absensi pertama user secara keseluruhan (untuk batas bawah global)
        $firstAttendanceGlobal = Attendance::where('enhancer', $id_user)
            ->orderBy('date')
            ->first();

        if (!$firstAttendanceGlobal) {
            // Tidak ada absensi sama sekali
            return view('pages.pegawai.attendance.printcustom', [
                'attendance' => collect(),
                'latitude' => null,
                'longitude' => null,
                'name' => $name,
                'countMasuk' => 0,
                'countPulang' => 0,
                'terlambat' => 0,
                'lebihAwal' => 0,
                'tidakMasuk' => 0,
            ]);
        }

        $firstAttendanceGlobalDate = Carbon::parse($firstAttendanceGlobal->date);

        // Tentukan awal dan akhir bulan input
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();

        // Ambil absensi di bulan dan tahun input
        $attendances = Attendance::where('enhancer', $id_user)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Jika tidak ada absensi di bulan ini, maka 0 semua
        if ($attendances->isEmpty()) {
            return view('pages.pegawai.attendance.printcustom', [
                'attendance' => collect(),
                'latitude' => null,
                'longitude' => null,
                'name' => $name,
                'countMasuk' => 0,
                'countPulang' => 0,
                'terlambat' => 0,
                'lebihAwal' => 0,
                'tidakMasuk' => 0,
            ]);
        }

        // Ambil tanggal absensi pertama di bulan ini
        $firstAttendanceInMonth = $attendances->sortBy('date')->first();
        $firstAttendanceInMonthDate = Carbon::parse($firstAttendanceInMonth->date);

        // Ambil koordinat dari absensi pertama di bulan ini
        $latitude = $firstAttendanceInMonth ? explode(',', $firstAttendanceInMonth->coordinate)[0] ?? null : null;
        $longitude = $firstAttendanceInMonth ? explode(',', $firstAttendanceInMonth->coordinate)[1] ?? null : null;

        // Ambil jadwal harian
        $scheduledays = collect();
        if ($scheduleId) {
            $schedule = Schedule::find($scheduleId);
            $scheduledays = $schedule ? ScheduleDayM::where('schedule_id', $schedule->id)->get() : collect();
        }

        // Kelompokkan absensi per tanggal
        $groupedAttendances = $attendances->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        $countMasuk = 0;
        $countPulang = 0;
        $terlambat = 0;
        $lebihAwal = 0;
        $tidakMasuk = 0;

        // Loop dari awal bulan sampai akhir bulan
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dayName = $date->locale('id')->dayName;

            // Cek ada jadwal hari ini?
            $scheduleDay = $scheduledays->firstWhere('days', $dayName);
            if (!$scheduleDay) {
                continue; // Tidak ada jadwal hari ini
            }

            $dateStr = $date->format('Y-m-d');
            $attendancesOnDate = $groupedAttendances[$dateStr] ?? collect();

            $masukToday = $attendancesOnDate->firstWhere('status', '0');
            $pulangToday = $attendancesOnDate->firstWhere('status', '1');

            if ($masukToday) {
                $countMasuk++;
                $attendanceTime = Carbon::parse($masukToday->created_at)->format('H:i:s');
                if ($attendanceTime > $scheduleDay->clock_in) {
                    $terlambat++;
                }
            } else {
                // Jangan hitung tidakMasuk kalau tanggal ini lebih kecil dari tanggal absensi pertama di bulan itu
                if ($date->gte($firstAttendanceInMonthDate)) {
                    $tidakMasuk++;
                }
            }

            if ($pulangToday) {
                $countPulang++;
                $attendanceTime = Carbon::parse($pulangToday->created_at)->format('H:i:s');
                if ($attendanceTime < $scheduleDay->clock_out) {
                    $lebihAwal++;
                }
            }
        }

        return view('pages.pegawai.attendance.printcustom', compact(
            'attendances',
            'latitude',
            'longitude',
            'name',
            'countMasuk',
            'countPulang',
            'terlambat',
            'lebihAwal',
            'tidakMasuk'
        ));
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


    public function send(Request $request, $id)
    {
        // dd($request->All());
        $user = User::find($id);
        if ($user) {
            // Simpan data peringatan
            $peringatan = new PeringatanM();
            $peringatan->status = $request->sp;
            $peringatan->totalDays = $request->sp;
            $peringatan->user_id = $id;
            $peringatan->save(); // Simpan dulu agar id muncul

            // dd($peringatan);
            // Sekarang $peringatan->id sudah ada, baru generate QR
            $qrContent = "Surat Peringatan\n"
                . "Nomor: " . $peringatan->id . "\n"
                . "Ditujukan untuk : " . $user->name . "\n"
                . "PT XYZ";

            // Generate dan simpan QR
            $fileName = 'qr_' . $peringatan->id . '.png';
            $filePath = 'public/qrSP/' . $fileName;

            // dd($fileName);
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($qrContent)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->build();

            Storage::put($filePath, $result->getString());

            // Simpan nama file QR ke model
            $peringatan->qr = $fileName;
            $peringatan->save(); // Update dengan nama file QR


            $qrPath = storage_path('app/public/qrSP/' . $peringatan->qr);
            $qrBase64 = base64_encode(file_get_contents($qrPath));
            $qrImage = 'data:image/png;base64,' . $qrBase64;

            $id_user = $user->id;
            $name = $user->name;
            $scheduleId = $user->schedule;

            // Ambil semua absensi user
            $attendanceMain = Attendance::where('enhancer', $id_user)->get();

            $attendance = $attendanceMain;
            $firstAttendance = $attendance->first();
            $latitude = $firstAttendance ? explode(',', $firstAttendance->coordinate)[0] ?? null : null;
            $longitude = $firstAttendance ? explode(',', $firstAttendance->coordinate)[1] ?? null : null;

            // Ambil jadwal harian
            $scheduledays = collect();
            if ($scheduleId) {
                $schedule = Schedule::find($scheduleId);
                $scheduledays = $schedule ? ScheduleDayM::where('schedule_id', $schedule->id)->get() : collect();
            }

            $countMasuk = 0;
            $countPulang = 0;
            $terlambat = 0;
            $lebihAwal = 0;
            $tidakMasuk = 0;

            // Kelompokkan absensi berdasarkan tanggal
            $groupedAttendances = $attendanceMain->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

            $firstDate = $attendanceMain->min('date');
            $lastDate = $attendanceMain->max('date');

            if ($firstDate && $lastDate) {
                $startDate = Carbon::parse($firstDate)->startOfDay();
                $endDate = Carbon::parse($lastDate)->endOfDay();

                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $dateStr = $date->format('Y-m-d');
                    $dayName = $date->locale('id')->dayName;

                    // Cek apakah hari ini ada jadwal
                    $scheduleDay = $scheduledays->firstWhere('days', $dayName);
                    if (!$scheduleDay) {
                        continue; // Hari ini tidak ada jadwal kerja
                    }

                    // Ambil absensi di tanggal ini
                    $attendancesOnDate = $groupedAttendances[$dateStr] ?? collect();

                    $masukToday = $attendancesOnDate->firstWhere('status', '0');
                    $pulangToday = $attendancesOnDate->firstWhere('status', '1');

                    if ($masukToday) {
                        $countMasuk++;
                        $attendanceTime = Carbon::parse($masukToday->created_at)->format('H:i:s');
                        if ($attendanceTime > $scheduleDay->clock_in) {
                            $terlambat++;
                        }
                    } else {
                        $tidakMasuk++;
                    }

                    if ($pulangToday) {
                        $countPulang++;
                        $attendanceTime = Carbon::parse($pulangToday->created_at)->format('H:i:s');
                        if ($attendanceTime < $scheduleDay->clock_out) {
                            $lebihAwal++;
                        }
                    }
                }
            }

            $data = [
                'user' => $user,
                'peringatan' => $peringatan,
                'qrImage' => $qrImage,
                'terlambat' => $terlambat,
                'tidakMasuk' => $tidakMasuk,
            ];
            // dd($data);

            // Buat PDF dan simpan dalam bentuk string
            $pdf = Pdf::loadView('pages.admin.attendance.contohsuratperingatan1', $data);
            $pdfContent = $pdf->output(); // isi file PDF

            $filename = 'Surat_Peringatan_SP-' . $peringatan->status . '_' . $user->name . '.pdf';
            // Kirim email dengan attachment
            Mail::to($user->email)->send(new AttendanceReminder($user, $pdfContent, $filename, $data));

            return redirect()->back()->with('success', 'Email dengan lampiran PDF telah dikirim');
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

    public function daftarsanksi()
    {
        $data = PeringatanM::orderBy('created_at', 'desc')->get();
        return view('pages.admin.attendance.daftarsanksi', compact('data'));
    }
    public function daftarsanksidetail($id)
    {
        $data = PeringatanM::findOrFail($id);
        $user = User::findOrFail($data->user_id);
        $qrPath = storage_path('app/public/qrSP/' . $data->qr);
        $qrBase64 = base64_encode(file_get_contents($qrPath));
        $qrImage = 'data:image/png;base64,' . $qrBase64;
        $pdf = Pdf::loadView('pages.admin.attendance.contohsuratperingatan2', compact('data', 'user', 'qrImage'));
        $filename = 'Surat_Peringatan_SP-' . $data->status . '_' . $user->name . '.pdf';

        return $pdf->download($filename);
    }

    public function download($filename)
    {
        // Decode filename if necessary
        $filename = urldecode($filename);

        // Path to the file in the storage/app/public folder
        $filePath = 'lampiran_cuti/' . $filename;

        // Check if the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            abort(Response::HTTP_NOT_FOUND, 'File not found');
        }

        // Return the file for download
        return Storage::disk('public')->download($filePath);
    }
}
