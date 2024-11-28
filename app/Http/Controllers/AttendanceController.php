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

class AttendanceController extends Controller
{
    public function kehadiran()
    {
        $user = User::where('role', '!=', 1)->pluck('id')->toArray();
        $userid = Auth::user()->id;
        $user = User::find($userid);
        $schedule = Schedule::find($user->schedule);
        $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();

        // Retrieve all attendances for display
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', '2'); // Assuming '2' is the role ID for 'pegawai'
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();


        return view('pages.admin.attendance.kelolakehadiranpegawai', compact('attendances', 'jadwal'));
    }



    public function filterKehadiran(Request $request)
    {
        $user = User::where('role', '!=', 1)->pluck('id')->toArray();
        $userid = Auth::user()->id;
        $user = User::find($userid);
        $schedule = Schedule::find($user->schedule);
        $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();

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

        return view('pages.admin.attendance.kelolakehadiranpegawai', compact('attendances', 'jadwal'));
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

        $lateAttendees = Attendance::where('status', 0)
            ->where('time', '>', Carbon::parse('08:00:00')->toDateTimeString())
            ->get()
            ->groupBy('user_id')
            ->filter(function ($attendances) {
                return $attendances->count() > 3;
            });

        $usersWithLateCount = $lateAttendees->map(function ($attendances) {
            return [
                'user_id' => $attendances->first()->enhancer,
                'late_count' => $attendances->count(),
            ];
        });

        return view('pages.admin.attendance.rekapitulasi', compact('calon', 'topUser', 'secondUser', 'thirdUser', 'usersWithLateCount'));
    }


    public function cetakrekap()
    {
        // Mengambil semua pengguna aktif
        $users = User::where('available', 10)->get();

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


    public function filtertanggal() {}


    public function cetakkehadiran()
    {
        $attendances = Attendance::whereHas('user', function ($query) {
            $query->where('role', '2'); // Gantilah 'pegawai' dengan ID role untuk pegawai jika menggunakan angka.
        })->get();

        return view('pages.admin.attendance.printkehadiranpegawai', compact('attendances'));
    }

    // Tampilkan daftar kehadiran
    public function index()
    {

        $userid = Auth::user()->id;
        $user = User::find($userid);
        $schedule = Schedule::find($user->schedule);
        $jadwal = ScheduleDayM::where('schedule_id', $schedule->id)->get();
        // dd($jadwal);

        $id = Auth::user()->id;
        // dd($id);
        $orang = Auth::user()->schedule;
        $jadwal = Schedule::where('id', $orang)->value('id');
        $jadwal_detail = ScheduleDayM::where('schedule_id', $jadwal)->get();
        $attendances = Attendance::where('enhancer', $id)->get();
        return view('pages.pegawai.attendance.index', compact('attendances', 'jadwal', 'jadwal_detail'));
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
        ]);

        Attendance::create([
            'enhancer' => Auth::id(),
            'date' => now()->format('Y-m-d'),
            'time' => now(),
            'status' => $request->input('status'),
            'coordinate' => $request->input('coordinate'),
        ]);
        return redirect()->route('pegawai.attendance')->with('success', 'Kehadiran berhasil disimpan.');
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

    public function cetakkehadiranmasuk($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('status', 0)
            ->firstOrFail(); // Mengambil data yang ada, atau gagal jika tidak ditemukan

        // dd($attendance); // Cek apakah data ditemukan

        $coordinates = explode(',', $attendance->coordinate);
        $latitude = $coordinates[0] ?? null;
        $longitude = $coordinates[1] ?? null;

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
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
