<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleDayM;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $data = User::where('role', 2)->get();
        $schedules = Schedule::get();
        return view('pages.admin.schedule.kelolajadwalpegawai', compact('schedules', 'data'));
    }

    public function create()
    {
        return view('pages.admin.schedule.tambahjadwal'); // Pastikan Anda menyesuaikan dengan nama view yang tepat
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $request->validate([
            'shift' => 'required|string',
            'abbreviation' => 'required|string',
            'day.*' => 'required|string',
            'clock_in.*' => 'required|date_format:H:i',
            'break.*' => 'required|date_format:H:i',
            'clock_out.*' => 'required|date_format:H:i',
        ]);

        $datascd = new Schedule();
        $datascd->shift_name = $request->shift;
        $datascd->singkatan = $request->abbreviation;
        $datascd->save();

        foreach ($request->day as $index => $d) {
            $data = new ScheduleDayM();
            $data->schedule_id = $datascd->id;
            $data->days = $d; // Set the specific day
            $data->clock_in = $request->clock_in[$index]; // Get the clock_in at the same index
            $data->break = $request->break[$index]; // Get the break at the same index
            $data->clock_out = $request->clock_out[$index]; // Get the clock_out at the same index
            $data->save(); // Save the record
        }


        return redirect()->route('admin.kelolajadwal')->with('success', 'Jadwal Pegawai berhasil ditambahkan!');
    }


    public function show(Schedule $schedule)
    {
        // Tampilkan detail jadwal
    }

    public function edit(Request $request, $id)
    {
        $schedules = Schedule::find($id);
        $dayes = ScheduleDayM::where('schedule_id', $id)->get();

        return view('pages.admin.schedule.editjadwal', compact('schedules', 'dayes'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'shift' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
            'day' => 'required|array',
            'clock_in' => 'required|array',
            'break' => 'required|array',
            'clock_out' => 'required|array',
        ]);

        // Find the schedule by ID
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }

        // Update schedule shift and abbreviation
        $schedule->shift_name = $request->input('shift');
        $schedule->Singkatan = $request->input('abbreviation');
        $schedule->save();

        $data = ScheduleDayM::where('schedule_id', $id)->delete();

        foreach ($request->day as $index => $d) {
            $data = new ScheduleDayM();
            $data->schedule_id = $schedule->id;
            $data->days = $d; // Set the specific day
            $data->clock_in = $request->clock_in[$index]; // Get the clock_in at the same index
            $data->break = $request->break[$index]; // Get the break at the same index
            $data->clock_out = $request->clock_out[$index]; // Get the clock_out at the same index
            $data->save(); // Save the record
        }


        return redirect()->route('admin.kelolajadwal')->with('success', 'Jadwal berhasil diperbaharui!');
    }

    public function destroy(Schedule $schedule)
    {
        // Hapus jadwal with('success','Jadwal berhasil dihapus dan dimasukkan ke dalam trash')
    }

    public function print()
    {
        $schedules = Schedule::get();
        return view('pages.admin.schedule.printjadwal', compact('schedules'));
    }

    public function delete($id)
    {
        $data = Schedule::find($id);
        $data->delete();

        $sch = ScheduleDayM::where('schedule_id', $data->id)->delete();

        return redirect()->back()->with('success', 'Jadwal Telah dihapus');
    }

    public function update_sch(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'schedule_id' => 'required|integer',
        ]);

        // Temukan data yang ingin diperbarui
        $employee = User::find($validatedData['id']);
        $employee->schedule = $validatedData['schedule_id'];
        $employee->save();

        return response()->json(['success' => true]);
    }
}
