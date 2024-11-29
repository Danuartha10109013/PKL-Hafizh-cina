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

class EmployeController extends Controller
{
    public function index()
    {
        $data = User::select('users.*', 'schedules.shift_name')
            ->join('schedules', 'users.schedule', '=', 'schedules.id')
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
        $validatedData = $request->validate([
            // 'username' => 'nullable|string|max:5|unique:users,username',
            'name' => 'required|string|regex:/^[A-Za-z\s]+$/|max:80',
            'role' => 'required|integer|exists:roles,id',
            'email' => 'nullable|string|email|max:80|unique:users,email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|regex:/[\W_]/',
        ]);
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
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'role' => 'required|integer|exists:roles,id',
            'email' => 'required|string|email|max:80|unique:users,email,' . $id,
            'password' => 'nullable|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            // 'status' => 'required|boolean',
            'schedule' => 'required|integer|exists:schedules,id',

            'telephone' => 'required|string|max:13',
            'status' => 'required|in:0,1',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'religion' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'id_card' => 'required|string|max:16',

        ]);

        // Find the user with their associated role and schedule
        $data = User::findOrFail($id);

        // Update user data except for password if it's empty
        $data->name = $validatedData['name'];
        $data->role = $validatedData['role'];
        $data->email = $validatedData['email'];
        $data->status = $validatedData['status'];
        $data->schedule = $validatedData['schedule'];

        $data->telephone = $validatedData['telephone'];
        $data->place_of_birth = $validatedData['place_of_birth'];
        $data->date_of_birth = $validatedData['date_of_birth'];
        $data->gender = $validatedData['gender'];
        $data->religion = $validatedData['religion'];
        $data->address = $validatedData['address'];
        $data->id_card = $validatedData['id_card'];


        // Update password only if provided
        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }

        // Handle avatar upload if any
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data->avatar = $avatarPath;
        }

        // Save the updated user
        $data->save();

        // Redirect with a success message
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
            ->join('schedules', 'users.schedule', '=', 'schedules.id')
            ->join('roles', 'users.role', '=', 'roles.id') // Join ke tabel roles untuk mengambil nama role
            ->where('users.role', 2)
            ->get();

        // Menampilkan view dengan data pegawai
        return view('pages.admin.managepegawai.printkelolapegawai', compact('data'));
    }
    public function input(Request $request)
    {
        // dd($request->all());
        Excel::import(new PegawaiImport, $request->file('pegawaiexcel'));
        return redirect()->route('admin.kelolapegawai')->with('success', 'pegawai telah berhasil ditambahkan');
    }
}
