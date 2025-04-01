<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('user')->orderBy('id', 'desc')->get();
        $leaves_annual = Leave::with('user')->orderBy('id', 'desc')->get();
        $leaves_etc = Leave::with('user')->get();
        $id_user = Auth::user()->id;
        $data = User::where('id', $id_user)->get();
        $name = User::where('id', $id_user)->value('name');

        // Menampilkan view dengan data pegawai
        return view('pages.admin.leave.kelolacuti', compact('leaves_annual', 'leaves', 'leaves_etc', 'name'));
    }

    public function show() {}

    public function create()
    {
        return view('pages.admin.leave.pengajuancuti');
    }

    public function store(Request $request)
    {
        // // Validasi data
        // $request->validate([
        //     'id' => 'required|exists:leaves,id',
        //     'status' => 'required|in:0,1',
        //     'reason' => 'nullable|string|max:255',
        // ]);

        // // Temukan record cuti
        // $leaves = Leave::findOrFail($request->id);

        // // Perbarui status cuti
        // $leaves->status = $request->status;
        // $leaves->reason = $request->status == '1' ? $request->reason : null; // Simpan alasan jika status 'Ditolak'
        // $leaves->save();

        // // Redirect kembali dengan pesan sukses
        // return redirect()->route('kelolacuti')->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }



    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validasi data
        $request->validate([
            'enhancer' => 'nullable',
            'id' => 'required|exists:leaves,id',
            // 'enhancer' => 'required|exists:users,id', // Pastikan enhancer ada di tabel users
            'status' => 'required|in:0,1',
            'reason' => 'nullable|string|max:255',
        ]);

        // Temukan record cuti
        $leave = Leave::findOrFail($id);

        // Perbarui status cuti
        $leave->status = $request->status;
        $leave->accepted_by = Auth::user()->id;
        $leave->accepted_time = now();
        $leave->reason_verification = $request->status == '1' ? $request->reason : null; // Simpan alasan jika status 'Ditolak'
        // $leave->enhancer = $request->enhancer; // Update enhancer
        if ($request->status == '0'){

            $atasan = User::find($leave->accepted_by);
            $qrContent = "Surat Izin Cuti\n"
            . "Nomor: $leave->no_surat\n"
            . "Disetujui oleh: $atasan->name\n"
            . "PT Pratama Solusi Teknologi";
    
        // Define file name & path
        $fileName = 'qr_' . $leave->id . '.png';
        $filePath = 'public/qrTtd/' . $fileName;
    
        // ✅ Generate QR Code using Endroid QR Code
        $result = Builder::create()
            ->writer(new PngWriter()) // Save as PNG
            ->data($qrContent) // QR Code Content
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->build();
            Storage::put($filePath, $result->getString());
    
            // Save the file name to the database
            $leave->qrCode_ttd = $fileName;

        $fName = 'qr_' . $leave->id . '.png';
        $fPath = 'public/qrApp/' . $fileName;
        $qrAppContent = "http://127.0.0.1:8000/qrcode/".$leave->id;
        $appQr = Builder::create()
            ->writer(new PngWriter()) // Save as PNG
            ->data($qrAppContent) // QR Code Content
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->build();
    
        // Save QR code image to storage
        Storage::put($fPath, $appQr->getString());
    
        // Save the file name to the database
        $leave->qrApp = $fName;
        }
        $leave->save();
        if ($request->status == 0) {
            $avail = User::where('id', $request->enhancer)->value('available');

            $daysleave = \Carbon\Carbon::parse($leave->date)->diffInDays($leave->end_date);
            $totalday = $avail - $daysleave;
            // dd($totalday);
            // Update the authenticated user’s available days
            $enhancer = User::where('id', $request->enhancer)->value('id');
            $user = User::findOrFail($enhancer);
            $user->available = $totalday;
            $user->save();
        }
        
        // dd($qrCode);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.kelolacuti')->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }


    public function cetakcuti()
    {
        $leaves = Leave::with('user')->get();
        return view('pages.admin.leave.printkelolacuti', compact('leaves'));
    }

    public function cetaksatuancuti()
    {
        return view('pages.admin.leave.printsatuancuti');
    }

    public function filtercuti() {}


    public function delete($id)
    {
        // Find the schedule to be deleted
        $schedule = Leave::findOrFail($id);
        $schedule->deleted_by = Auth::user()->id;
        $schedule->save();
        $schedule->delete();

        return redirect()->back()->with('success', 'Cuti telah dihapus');
    }


    public function restore($id)
    {

        $att = Leave::withTrashed()->find($id);
        $att->deleted_by = null;
        $att->save();
        // Restore the specific Schedule record with the given id
        Leave::withTrashed()->where('id', $id)->restore();

        return redirect()->back()->with('success', 'Cuti telah dipulihkan');
    }


    public function forceDelete($id)
    {
        $att = Leave::withTrashed()->findOrFail($id);
        $att->forceDelete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Cuti telah dihapus secara permanen');
    }
}
