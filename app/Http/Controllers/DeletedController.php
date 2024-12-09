<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class DeletedController extends Controller
{
    //user delete
    public function deleteuser($id)
    {
        $data = User::find($id);
        // dd($data->deleted_by);
        $data->deleted_by = Auth::user()->id;
        $data->save();
        $data->delete();


        // Cari semua attendances yang terkait dengan enhancer
        $attendances = Attendance::where('enhancer', $id)->get();
        if (!$attendances->isEmpty()) {
            foreach ($attendances as $attendance) {
                $attendance->deleted_by = Auth::user()->id;
                $attendance->save(); // Update sebelum dihapus
            }
            Attendance::where('enhancer', $id)->delete(); // Hapus semua attendances terkait
        }

        // Cari semua leaves yang terkait dengan enhancer
        $leaves = Leave::where('enhancer', $id)->get();
        if (!$leaves->isEmpty()) {
            foreach ($leaves as $leave) {
                $leave->deleted_by = Auth::user()->id;
                $leave->save(); // Update sebelum dihapus
            }
            Leave::where('enhancer', $id)->delete(); // Hapus semua leaves terkait
        }


        return redirect()->back()->with('success', 'Pegawai berhasil dihapus, dan dimasukan ke dalam trash');
    }

    public function destroyuser($id)
    {
        // Retrieve soft deleted user
        $data = User::onlyTrashed()->find($id);

        if ($data) {
            // Delete related attendance and leave records
            Attendance::where('enhancer', $id)->forceDelete();
            Leave::where('enhancer', $id)->forceDelete();

            // Permanently delete the user
            $data->forceDelete();

            return redirect()->back()->with('success', 'Pegawai telah dihapus secara permanen');
        }

        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    public function restoreuser($id)
    {
        $data = User::withTrashed()->find($id);

        //delete juga data yang terkiait
        Attendance::withTrashed('enhancer', $id)->restore();
        Leave::withTrashed('enhancer', $id)->restore();
        // dd($id);
        $data->restore();
        return redirect()->back()->with('success', 'Pegawai telah dipulihkan');
    }

    public function index()
    {
        // Mengambil data pegawai dari database
        $data = User::with('role', 'schedule')->get();

        // $deleteduser = User::where('delete_at' != null)->get();
        $deletedUsers = User::onlyTrashed()->get();
        $deleteby = User::onlyTrashed()->value('deleted_by');
        $nama = User::where('id', $deleteby)->value('name');
        $schedules = Schedule::onlyTrashed()->get();
        $deletedAttendances = Attendance::onlyTrashed()->get();
        $leaves = Leave::onlyTrashed()->get();



        // dd($nama);

        // Menampilkan view dengan data pegawai
        return view('pages.admin.trash.trashed', compact('data', 'deletedUsers', 'nama','schedules','deletedAttendances','leaves'));
    }
}
