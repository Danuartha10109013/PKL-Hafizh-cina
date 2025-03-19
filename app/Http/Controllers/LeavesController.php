<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeavesController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user's ID
        $id_user = Auth::user()->id;

        // Fetch leaves associated with the currently authenticated user
        $leaves = Leave::where('enhancer', $id_user)->with('user')->get();

        // Fetch the name of the currently authenticated user
        $name = User::where('id', $id_user)->value('name');

        // Pass the data to the view
        return view('pages.pegawai.leaves.index', compact('leaves', 'name'));
    }



    public function create()
    {
        return view('pages.pegawai.leaves.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi data input
        $validatedData = $request->validate([
            'enhancer' => 'required',
            'reason' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'leave_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Validasi file
            'date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:date',
        ]);

        // Inisialisasi objek Leave
        $leave = new Leave();

        // Konversi tanggal
        $leave->date = Carbon::createFromFormat('d M Y', $request->input('date'))->format('Y-m-d');
        $leave->end_date = Carbon::createFromFormat('d M Y', $request->input('end_date'))->format('Y-m-d');

        // Simpan file leave_letter jika ada
        if ($request->hasFile('leave_letter')) {
            // Ambil file asli
            $file = $request->file('leave_letter');
            // Ganti spasi dengan underscore
            $fileName = str_replace(' ', '-', $file->getClientOriginalName());
            // Simpan file ke direktori public/lampiran_cuti dengan nama baru
            $leaveLetterPath = $file->move(public_path('storage/lampiran_cuti'), $fileName);
            // Simpan nama file ke database
            $leave->leave_letter = $fileName;
        }

        // Set data lain yang sudah tervalidasi
        $leave->enhancer = $validatedData['enhancer'];
        $leave->reason = $validatedData['reason'];
        $leave->category = $validatedData['category'];
        $leave->subcategory = $validatedData['subcategory'];
        $currentYear = date('Y');
        $currentMonth = date('n'); // Numeric month (1-12)

        // Convert month to Roman numeral
        $romanMonths = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $romanMonth = $romanMonths[$currentMonth];

        // Get the last leave number of the current month and year
        $lastLeave = \App\Models\Leave::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->latest('id')
            ->first();

        if ($lastLeave) {
            // Extract the number part and increment
            preg_match('/^(\d{4})/', $lastLeave->no_surat, $matches);
            $newNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
        } else {
            $newNumber = 1;
        }

        // Format number with leading zeros (0001, 0002, etc.)
        $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Generate the new no_surat
        $no_surat = "{$formattedNumber}/Leave-PR/{$romanMonth}/{$currentYear}";

        // dd( $no_surat);
        $leave->no_surat = $no_surat;
        $leave->save();

        return redirect()->route('pegawai.leaves')->with('success', 'Pengajuan cuti berhasil ditambahkan dan sedang menunggu konfirmasi.');
    }



    public function edit($id)
    {
        $leave = Leave::with('user')->findOrFail($id);
        return view('pages.pegawai.leaves.edit', compact('leave'));
    }


    public function update(Request $request, $id)
    {
        // Find the leave record
        $leave = Leave::findOrFail($id);

        // Common validations
        $request->validate([
            'category' => 'required|string|in:annual,other',
            'reason' => 'required|string|max:255',
            'date' => 'required|date_format:d M Y',
            'end_date' => 'required|date_format:d M Y|after_or_equal:date',
        ]);

        // Specific validations for 'other' category
        if ($request->category === 'other') {
            $request->validate([
                'subcategory' => 'required|string|in:sick,married,important_reason,pilgrimage',
                'leave_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Optional file for 'other'
            ]);
        }

        // Update common fields
        $leave->category = $request->input('category');
        $leave->reason = $request->input('reason');
        $leave->date = \Carbon\Carbon::createFromFormat('d M Y', $request->input('date'))->format('Y-m-d');
        $leave->end_date = \Carbon\Carbon::createFromFormat('d M Y', $request->input('end_date'))->format('Y-m-d');

        // Update 'other' specific fields
        if ($request->category === 'other') {
            $leave->subcategory = $request->input('subcategory');

            // Handle leave letter file upload
            if ($request->hasFile('leave_letter')) {
                // Delete old file if it exists
                if ($leave->leave_letter && Storage::exists('leave_letters/' . $leave->leave_letter)) {
                    Storage::delete('leave_letters/' . $leave->leave_letter);
                }

                // Store new file
                $file = $request->file('leave_letter');
                $filename = time() . '-' . $file->getClientOriginalName();
                $file->storeAs('leave_letters', $filename, 'public');

                // Update file path in the database
                $leave->leave_letter = $filename;
            }
        } else {
            // Clear fields specific to 'other' category when it's 'annual'
            $leave->subcategory = null;
            if ($leave->leave_letter && Storage::exists('leave_letters/' . $leave->leave_letter)) {
                Storage::delete('leave_letters/' . $leave->leave_letter);
            }
            $leave->leave_letter = null;
        }

        // Save updated leave record
        $leave->save();

        // Redirect with success message
        return redirect()->route('pegawai.leaves')->with('success', 'Cuti berhasil diperbarui.');
    }




    public function filtercuti(Request $request)
    {
        // Get the currently authenticated user's ID
        $id_user = Auth::user()->id;

        // Initialize a query builder for the leaves table
        $query = Leave::where('enhancer', $id_user);

        // Filter by category if a category is selected
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by status if a status is selected
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Get the filtered leaves with associated user data
        $leaves = $query->with('user')->get();

        // Fetch the name of the currently authenticated user
        $name = User::where('id', $id_user)->value('name');

        // Return the filtered data to the view
        return view('pages.pegawai.leaves.index', compact('leaves', 'name'));
    }


    public function print($id)
    {
        $id_user = Auth::user()->id;
        $leaves = Leave::where('enhancer', $id_user)->with('user')->value('id');
        $l = Leave::find($id);
        $name = User::where('id', $id_user)->value('name');
        $leaves = null;
        // dd($leaves);
        return view('pages.pegawai.leaves.print', compact('l', 'leaves'));
    }
    public function printall(Request $request)
    {
        // dd($request->all());
        $leaves = Leave::where('enhancer', $request->id)
            ->where('status', $request->status)
            ->where('category', $request->category)
            ->get();

        if ($leaves->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        return view('pages.pegawai.leaves.print', compact('leaves'));
    }
}
