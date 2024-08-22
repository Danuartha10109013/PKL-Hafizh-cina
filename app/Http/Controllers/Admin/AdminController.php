<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();
        $cuti = Leave::all();  // Gunakan Leave::all() alih-alih Leave::get()

        // Menampilkan view dengan data pegawai
        return view('pages.admin.dashboard', compact('data', 'cuti'));
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

    public function persetujuancuti()
    {
        return view('pages.admin.leave.pengajuancuti');
    }


    public function editcuti()
    {
        return view('pages.admin.leave.editcuti');
    }
}
