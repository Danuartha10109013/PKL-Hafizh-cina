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
                                    <th>Waktu</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                                {{-- @foreach ($deletedAttendances as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->tanggal }}</td>
                                        <td>{{ $attendance->pegawai->name }}</td>
                                        <td>{{ $attendance->deletedBy->name }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.attendancerestore', $attendance->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.attendancedestroyed', $attendance->id) }}"
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
                                @endforeach --}}
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
                                {{-- @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->pegawai->name }}</td>
                                        <td>{{ $schedule->tanggal }}</td>
                                        <td>{{ $schedule->shift }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.schedulerestore', $schedule->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.scheduledestroyed', $schedule->id) }}"
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
                                @endforeach --}}
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
                                {{-- @foreach ($leaves as $leave)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $leave->pegawai->name }}</td>
                                        <td>{{ $leave->tanggal_mulai }}</td>
                                        <td>{{ $leave->tanggal_selesai }}</td>
                                        <td>{{ $leave->keterangan }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.leaverestore', $leave->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.leavedestroyed', $leave->id) }}"
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
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
