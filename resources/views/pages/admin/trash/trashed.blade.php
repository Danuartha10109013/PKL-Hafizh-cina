@extends('layout.main')

@section('title')
    Recycle Bin
@endsection

@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <h4 class="nk-block-title">Daftar Data yang Dihapus</h4>
                </div>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}'
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: '{{ session('error') }}'
                        });
                    </script>
                @endif

                {{-- Tabel Daftar Pegawai yang Dihapus --}}
                <div class="card card-bordered card-preview mb-4">
                    <div class="card-inner">
                        <h5 class="nk-block-title mb-3 text-center">Pegawai</h5>
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deletedUsers as $userd)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $userd->name }}</td>
                                        <td>{{ $userd->email }}</td>
                                        <td>
                                            @php
                                                $nama = \App\Models\User::where('id', $userd->deleted_by)->value(
                                                    'name',
                                                );
                                            @endphp
                                            {{ $nama }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.userrestore', $userd->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.userdestroyed', $userd->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-na"></em><span>Delete
                                                            Permanantly</span></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Daftar Kehadiran yang Dihapus --}}
                <div class="card card-bordered card-preview mb-4">
                    <div class="card-inner">
                        <h5 class="nk-block-title mb-3 text-center">Kehadiran</h5>
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pegawai</th>
                                    <th>Status</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                                @foreach ($deletedAttendances as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->date }}</td>
                                        <td>@php
                                            $names = \App\Models\User::where('id',$attendance->enhancer)->value('name')
                                        @endphp
                                        {{$names}}</td>
                                        <td>
                                            @if ($attendance->status == 0)
                                                    <span class="badge bg-success">Masuk</span>
                                                @elseif ($attendance->status == 1)
                                                    <span class="badge bg-danger">Pulang</span>
                                                @endif
                                        </td>   
                                        <td>@php
                                            $name = \App\Models\User::where('id',$attendance->deleted_by)->value('name')
                                        @endphp
                                        {{$name}}</td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{route('admin.kelolakehadiranpegawai.restore',$attendance->id)}}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{route('admin.kelolakehadiranpegawai.forcedelete',$attendance->id)}}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-na"></em><span>Delete
                                                            Permanantly</span></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Jadwal --}}
                <div class="card card-bordered card-preview mb-4">
                    <div class="card-inner">
                        <h5 class="nk-block-title mb-3 text-center">Jadwal</h5>
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shift</th>
                                    <th>Waktu Kerja</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->shift_name }}</td>
                                        <td>
                                            @php
                                                // Retrieve the schedule days
                                                $datasd = \App\Models\ScheduleDayM::withTrashed()->where('schedule_id', $schedule->id)->get();
                                                // dd($datasd);
                                                // Initialize the total hours
                                                $total = 0;

                                                if ($datasd->isEmpty()) {
                                                    // If $datasd is empty, set total to "N/A"
                                                    $total = 'N/A';
                                                } else {
                                                    foreach ($datasd as $data) {
                                                        // Convert clock_in and clock_out to Carbon instances, and handle null values
                                                        $clockIn = $data->clock_in ? \Carbon\Carbon::parse($data->clock_in) : null;
                                                        $clockOut = $data->clock_out ? \Carbon\Carbon::parse($data->clock_out) : null;

                                                        // Calculate the difference in hours if both times are available
                                                        if ($clockIn && $clockOut) {
                                                            $hours = $clockOut->diffInHours($clockIn);
                                                            $total += $hours;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            {{ is_numeric($total) ? number_format(abs($total), 2, '.', '') : $total }} Jam


                                        </td>
                                        
                                        
                                        <td>
                                            @php
                                                $name = \App\Models\User::where('id',$schedule->deleted_by)->value('name')
                                            @endphp
                                            {{$name}}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.restore-jadwal', $schedule->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.forcedelete-jadwal', $schedule->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-na"></em><span>Delete
                                                            Permanantly</span></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Cuti --}}
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <h5 class="nk-block-title mb-3 text-center">Cuti</h5>
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Alasan</th>
                                    <th>Pengajuan</th>
                                    <th>Mulai</th>
                                    <th>Berakhir</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @php
                                            $nam = \App\Models\User::where('id',$leave->enhancer)->value('name')
                                        @endphp
                                        {{$nam}}
                                        </td>
                                        <td>{{ $leave->reason }}</td>
                                        <td>{{ $leave->category }}</td>
                                        <td>{{ $leave->date }}</td>
                                        <td>{{ $leave->end_date }}</td>
                                        <td>
                                            @php
                                                $name = \App\Models\User::where('id',$leave->deleted_by)->value('name')
                                            @endphp
                                            {{$name}}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{route('admin.restore-satuancuti',$leave->id)}}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{route('admin.forcedelete-satuancuti',$leave->id)}}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-na"></em><span>Delete
                                                            Permanantly</span></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
