<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PegawaiImport;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class EmployeController extends Controller
{
    public function index()
    {
        $data = User::select('users.*', 'schedules.shift_name')
            ->leftjoin('schedules', 'users.schedule', '=', 'schedules.id')
            ->where('users.role', 2)
            ->get();
        // Mengambil data pegawai dari database
        // $data = User::with('role', 'schedule')->get();
        // $data = User::where('role', 2)->get();
        // $deleteduser = User::where('delete_at' != null)->get();
        $deletedUsers = User::onlyTrashed()->get();
        $deleteby = User::onlyTrashed()->value('deleted_by');
        $nama = User::where('id', $deleteby)->value('name');


        // dd($nama);

        // Menampilkan view dengan data pegawai
        return view('pages.admin.managepegawai.kelolapegawai', compact('data', 'deletedUsers', 'nama'));
    }

    public function show($id)
    {
        $item = User::findOrFail($id);
        $roles = Role::all();
        $schedule = Schedule::where('id', $item->schedule)->first();

        // Jika jadwal tidak ditemukan, set default null atau data dummy
        if (!$schedule) {
            $schedule = null; // atau buat objek kosong: $schedule = new Schedule();
        }

        return view('pages.admin.managepegawai.detailkelolapegawai', compact('item', 'roles', 'schedule'));
    }



    public function create()
    {
        // Mengambil data roles untuk ditampilkan di dropdown
        $roles = Role::all();
        $schedules = Schedule::all();

        // Hitung jumlah user yang ada untuk menentukan username
        $lastUsername = User::where('username', '!=', 'admin')->orderBy('username', 'desc')->value('username');
        // dd($lastUsername);
        // Cek apakah ada username, jika tidak set default
        if ($lastUsername) {
            // Ambil angka dari username terakhir dan tambahkan satu
            $number = (int) filter_var($lastUsername, FILTER_SANITIZE_NUMBER_INT);
            $newUsernameNumber = $number + 1;
        } else {
            // Jika belum ada username, mulai dari 1
            $newUsernameNumber = 1;
        }

        // Format username dengan 5 digit angka, menggunakan padding nol di sebelah kiri
        $nextUsername = str_pad($newUsernameNumber, 5, '0', STR_PAD_LEFT);

        // Menampilkan view form tambah pegawai dengan username yang sudah di-generate
        return view('pages.admin.managepegawai.tambahpegawai', compact('roles', 'schedules', 'nextUsername'));
    }




    public function store(Request $request)
    {
        // Validate the request data
        // dd($request->all());
        $validatedData = $request->validate(
            [
                // 'username' => 'nullable|string|max:5|unique:users,username',
                'name' => 'required|string|regex:/^[A-Za-z\s]+$/|max:80',
                'role' => 'required|integer|exists:roles,id',
                'email' => 'nullable|string|email|max:80|unique:users,email',
                'password' => 'required|string',
                'position' => 'required|string',
                'nip' => 'required',
                // 'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|regex:/[\W_]/',
            ],
            [
                'email.unique' => 'Email sudah digunakan.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari 80 karakter.',
                'name.required' => 'Nama tidak boleh kosong.',
                'name.regex' => 'Nama hanya boleh huruf dan spasi.',
                'role.required' => 'Role wajib dipilih.',
                'role.exists' => 'Role tidak valid.',
                'password.required' => 'Password tidak boleh kosong.',
                'position.required' => 'Jabatan harus diisi.',
                'nip.required' => 'NIP tidak boleh kosong.',
            ]
        );
        // Hitung jumlah user yang ada untuk menentukan username
        $lastUsername = User::where('username', '!=', 'admin')->orderBy('username', 'desc')->value('username');
        // dd($lastUsername);
        // Cek apakah ada username, jika tidak set default
        if ($lastUsername) {
            // Ambil angka dari username terakhir dan tambahkan satu
            $number = (int) filter_var($lastUsername, FILTER_SANITIZE_NUMBER_INT);
            $newUsernameNumber = $number + 1;
        } else {
            // Jika belum ada username, mulai dari 1
            $newUsernameNumber = 1;
        }

        // Format username dengan 5 digit angka, menggunakan padding nol di sebelah kiri
        $validatedData['username'] = str_pad($newUsernameNumber, 5, '0', STR_PAD_LEFT);
        // Create a new user instance
        $user = new User($validatedData);

        // Hash the password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user['token'] = Str::random(32);

        // Save the user
        $user->save();

        // Redirect with a success message
        return redirect()->route('admin.kelolapegawai')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        $roles = Role::all();
        $schedules = Schedule::all();
        return view('pages.admin.managepegawai.editpegawai', compact('item', 'roles', 'schedules'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:80',
            'email' => 'nullable|string|email|max:80|unique:users,email,' . $id,
            'password' => 'nullable',
            // 'password' => 'nullable|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            'schedule' => 'nullable|integer|exists:schedules,id',
            'telephone' => 'nullable|string|max:13',
            'status' => 'nullable|in:0,1',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'id_card' => 'nullable|string|max:16',
            'nip' => 'nullable|string',
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update hanya field yang dikirim
        $user->fill(array_filter($validatedData));

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle avatar upload jika ada
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
        if ($request->hasFile('acuan')) {
            $acuanPath = $request->file('acuan')->store('acuan', 'public');
            $user->acuan = $acuanPath;
        }

        // Simpan perubahan
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.kelolapegawai')->with('success', 'Pegawai berhasil diperbaharui.');
    }





    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);

    //     if (!$user) {
    //         return redirect()->route('admin.kelolapegawai')->with('error', 'Pegawai tidak ditemukan');
    //     }

    //     $user->delete();

    //     return redirect()->route('admin.kelolapegawai')->with('success', 'Pegawai berhasil dihapus.');
    // }

    // public function trashed()
    // {
    //     $data = User::onlyTrashed()->get();

    //     return view('pages.admin.managepegawai.trashed', compact('data'));
    // }

    // public function restore($id)
    // {
    //     $user = User::onlyTrashed()->findOrFail($id);
    //     $user->restore();

    //     return redirect()->route('admin.kelolapegawai')->with('success', 'Pegawai berhasil dikembalikan.');
    // }



    public function cetakpegawai()
    {
        // Menggunakan join antara tabel 'users', 'schedules', dan 'roles'
        $data = User::select('users.*', 'schedules.shift_name', 'roles.name as role_name')
            ->leftjoin('schedules', 'users.schedule', '=', 'schedules.id')
            ->join('roles', 'users.role', '=', 'roles.id') // Join ke tabel roles untuk mengambil nama role
            ->where('users.role', 2)
            ->get();

        // Menampilkan view dengan data pegawai
        return view('pages.admin.managepegawai.printkelolapegawai', compact('data'));
    }
    public function input(Request $request)
    {
        // dd($request->all());
        FacadesExcel::import(new PegawaiImport, $request->file('pegawaiexcel'));
        return redirect()->route('admin.kelolapegawai')->with('success', 'pegawai telah berhasil ditambahkan');
    }
}
