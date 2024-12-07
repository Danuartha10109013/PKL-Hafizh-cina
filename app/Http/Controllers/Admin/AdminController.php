<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // // Mengambil data pegawai dari database
        // $data = User::with('role', 'schedule')->get();
        // $cuti = Leave::all(); // Mengambil semua data cuti

        // // Mengambil semua user kecuali admin (role != 1)
        // $user = User::where('role', '!=', 1)->pluck('id')->toArray();

        // // Menghitung pegawai hadir tepat waktu HARI INI
        // $tepatHariIni = Attendance::whereRaw("TIME(time) < '08:00:00'")
        //     ->whereIn('enhancer', $user)
        //     ->whereDate('time', Carbon::today()) // untuk menampilkan data sesuai tanggal dan hari ini
        //     ->get();

        // // Menghitung pegawai terlambat HARI INI
        // $telatHariIni = Attendance::whereRaw("TIME(time) > '08:00:00'")
        //     ->whereIn('enhancer', $user)
        //     ->whereDate('time', Carbon::today()) // untuk menampilkan data sesuai tanggal dan hari ini
        //     ->get();

        // // Menghitung total seluruh pegawai yang hadir tepat waktu (tidak dibatasi hari ini)
        // $totalTepat = Attendance::whereRaw("TIME(time) < '08:00:00'")
        //     ->whereIn('enhancer', $user)
        //     ->count(); // Menghitung semua yang hadir tepat waktu

        // // Menghitung total seluruh pegawai yang terlambat (tidak dibatasi hari ini)
        // $totalTelat = Attendance::whereRaw("TIME(time) > '08:00:00'")
        //     ->whereIn('enhancer', $user)
        //     ->count(); // Menghitung semua yang terlambat

        // // Menampilkan view dengan data pegawai, cuti, tepat, telat, dan total data
        // return view('pages.admin.dashboard', compact('data', 'cuti', 'tepatHariIni', 'telatHariIni', 'totalTepat', 'totalTelat'));

        // Mengambil data pegawai (kecuali admin) dari database
        $data = User::with('role', 'schedule')->where('role', '!=', 1)->get();
        $cuti = Leave::all(); // Mengambil semua data cuti

        // Mengambil semua user kecuali admin (role != 1)
        $userIds = $data->pluck('id')->toArray();

        // Statistik kehadiran
        $hariIni = Carbon::today();

        $masuk = Attendance::where('status', 0)
            ->whereIn('enhancer', $userIds)
            // ->whereDate('date', $hariIni)
            ->count();

        $keluar = Attendance::where('status', 1)
            ->whereIn('enhancer', $userIds)
            // ->whereDate('date', $hariIni)
            ->count();


        // Absen dihitung sebagai data masuk dan keluar
        $absen = $masuk + $keluar;

        // Total cuti hari ini
        $cutiHariIni = Leave::whereIn('enhancer', $userIds)
            // ->whereDate('date', $hariIni)
            ->count();

        // Menghitung pegawai hadir tepat waktu HARI INI
        $tepatHariIni = Attendance::whereRaw("TIME(time) < '08:00:00'")
            ->whereIn('enhancer', $userIds)
            ->whereDate('time', $hariIni)
            ->get();

        // Menghitung pegawai terlambat HARI INI (ambil data sebenarnya, bukan hanya count)
        $telatHariIni = Attendance::whereRaw("TIME(time) >= '08:00:00'")
            ->whereIn('enhancer', $userIds)
            ->whereDate('time', $hariIni)
            ->get(); // Use get() to get the actual records


        // Total kehadiran tepat waktu (tidak dibatasi hari ini)
        $totalTepat = Attendance::whereRaw("TIME(time) < '08:00:00'")
            ->whereIn('enhancer', $userIds)
            ->count();

        // Total kehadiran terlambat (tidak dibatasi hari ini)
        $totalTelat = Attendance::whereRaw("TIME(time) >= '08:00:00'")
            ->whereIn('enhancer', $userIds)
            ->count();



        // Menghitung total pegawai berdasarkan gender
        $genderCounts = DB::table('users')
            ->select(DB::raw("gender, COUNT(*) as total"))
            ->groupBy('gender')
            ->pluck('total', 'gender')->toArray();

        $maleCount = $genderCounts['Laki - laki'] ?? 0;
        $femaleCount = $genderCounts['Perempuan'] ?? 0;

        // Gather all attendance stats for the chart
        $attendanceStats = [
            'cuti' => $cutiHariIni,
            'masuk' => $masuk,
            'pulang' => $keluar,
            'absen' => $absen,
        ];

        // Menampilkan view dengan semua data yang dihitung
        return view('pages.admin.dashboard', compact(
            'data',
            'cuti',
            'tepatHariIni',
            'telatHariIni',
            'totalTepat',
            'totalTelat',
            'masuk',
            'keluar',
            'absen',
            'cutiHariIni',
            'maleCount',
            'femaleCount',
            'attendanceStats'
        ));
    }



    public function cuti()
    {
        $data = User::with('role', 'schedule')->get();
        $cuti = Leave::all();  // Gunakan Leave::all() alih-alih Leave::get()

        // Menampilkan view dengan data pegawai
        return view('pages.admin.leave.kelolacuti', compact('data', 'cuti'));
    }

    public function cetakcuti()
    {
        $data = User::with('role', 'schedule')->get();
        $cuti = Leave::all();  // Gunakan Leave::all() alih-alih Leave::get()

        // Menampilkan view dengan data pegawai
        return view('pages.admin.leave.printkelolacuti', compact('data', 'cuti'));
    }

    public function cetaksatuancuti()
    {
        $data = User::with('role', 'schedule')->get();
        $cuti = Leave::all();  // Gunakan Leave::all() alih-alih Leave::get()

        // Menampilkan view dengan data pegawai
        return view('pages.admin.leave.printsatuancuti', compact('data', 'cuti'));
    }

    public function persetujuancuti()
    {
        return view('pages.admin.leave.pengajuancuti');
    }


    public function editcuti()
    {
        return view('pages.admin.leave.editcuti');
    }
}
