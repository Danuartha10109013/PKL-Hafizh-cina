<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserControllers1 extends Controller
{
    public function index()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();
        $id = Auth::user()->id;
        // dd($id);
        $cuti = Leave::all();  // Gunakan Leave::all() alih-alih Leave::get()
        $attendances = Attendance::where('enhancer', $id)->get();
        return view('pages.pegawai.dashboard', compact('data', 'cuti', 'attendances'));
    }

    public function profilakun()
    {
        // Mengambil data pegawai dari database
        $id_user = Auth::user()->id;
        $data = User::where('id', $id_user)->get();
        $same = User::where('id', $id_user)->value('role');
        $role = Role::where('id', $same)->value('name');
        $same2 = User::where('id', $id_user)->value('schedule');
        $schedule = Schedule::where('id', $same2)->value('shift_name');
        $act = User::where('id', $id_user)->value('status');
        if ($act == 0) {
            $active = "Aktif";
        } else {
            $active = "Tidak Aktif";
        }
        // $sched = User::where('id', $id_user)->value('schedule');
        // if ($act == 0) {
        //     $schedule = "Shift 1";
        // } else {
        //     $schedule = "Shift 2";
        // }

        return view('pages.pegawai.profil.profilakun', compact('data', 'role', 'active', 'schedule'));
    }

    public function profilbiodata()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();

        return view('pages.pegawai.profil.profilbiodata', compact('data'));
    }

    public function profilidentitas()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();

        return view('pages.pegawai.profil.profilidentitas', compact('data'));
    }

    public function updates(Request $request, $id)
    {
        $validatedData = $request->validate([
            'email' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:15',
            'status' => 'nullable|in:0,1',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'id_card' => 'nullable|string|regex:/^\d{1,16}$/',
        ]);

        // Find the user or fail
        $user = User::findOrFail($id);

        // Jika tanggal lahir diinputkan, konversi ke format yang bisa disimpan di database (Y-m-d)
        if ($request->filled('date_of_birth')) {
            $validatedData['date_of_birth'] = \Carbon\Carbon::createFromFormat('d M Y', $request->date_of_birth)->format('Y-m-d');
        }

        // Update the user with validated data
        $user->update($validatedData);
        return redirect(url('/pegawai/profil/akun'))->with('success', 'Data identitas berhasil diperbarui.');
    }


    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::findOrFail($id);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);
        $user->avatar = 'images/' . $imageName;

        $user->save();


        return redirect()->back()->with('success', 'Avatar berhasil diperbaharui!');
    }
}
