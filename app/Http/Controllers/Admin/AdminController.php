<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function index()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();
        $cuti = Leave::all(); // Mengambil semua data cuti

        // Mengambil semua user kecuali admin (role != 1)
        $user = User::where('role', '!=', 1)->pluck('id')->toArray();

        // Menghitung pegawai hadir tepat waktu HARI INI
        $tepatHariIni = Attendance::whereRaw("TIME(time) < '08:00:00'")
            ->whereIn('enhancer', $user)
            ->whereDate('time', Carbon::today()) // untuk menampilkan data sesuai tanggal dan hari ini
            ->get();

        // Menghitung pegawai terlambat HARI INI
        $telatHariIni = Attendance::whereRaw("TIME(time) > '08:00:00'")
            ->whereIn('enhancer', $user)
            ->whereDate('time', Carbon::today()) // untuk menampilkan data sesuai tanggal dan hari ini
            ->get();

        // Menghitung total seluruh pegawai yang hadir tepat waktu (tidak dibatasi hari ini)
        $totalTepat = Attendance::whereRaw("TIME(time) < '08:00:00'")
            ->whereIn('enhancer', $user)
            ->count(); // Menghitung semua yang hadir tepat waktu

        // Menghitung total seluruh pegawai yang terlambat (tidak dibatasi hari ini)
        $totalTelat = Attendance::whereRaw("TIME(time) > '08:00:00'")
            ->whereIn('enhancer', $user)
            ->count(); // Menghitung semua yang terlambat

        // Menampilkan view dengan data pegawai, cuti, tepat, telat, dan total data
        return view('pages.admin.dashboard', compact('data', 'cuti', 'tepatHariIni', 'telatHariIni', 'totalTepat', 'totalTelat'));
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
