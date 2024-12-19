@extends('layout.main')
@section('title')
    Rekapitulasi Kehadiran
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Rekapitulasi Kehadiran</h3>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li><a href="{{ route('admin.print-cetakrekapitulasi') }}"
                                                    class="btn btn-secondary" target="_blank"><em
                                                        class="icon ni ni-printer"></em><span>Cetak</span></a></li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: '{{ session('success') }}',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        </script>
                    @endif
                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '{{ session('error') }}',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        </script>
                    @endif
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pegawai</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Pulang Lebih Awal</th>
                                            <th>Terlambat Masuk</th>
                                            <th>Tidak Masuk</th>
                                            <th>Cuti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>@php
                                                    $name = \App\Models\User::where('id', $d->enhancer)->value('name');
                                                    $ids = \App\Models\User::where('id', $d->enhancer)->value('id');
                                                    // dd($ids);
                                                @endphp
                                                    {{ $name }}
                                                </td>
                                                <td>
                                                    @php
                                                        $countmasuk = \App\Models\Attendance::where('enhancer', $ids)
                                                            ->where('status', '0')
                                                            ->count();
                                                        // dd($countmasuk);
                                                    @endphp
                                                    {{ $countmasuk }}
                                                </td>
                                                <td>
                                                    @php
                                                        $countpulang = \App\Models\Attendance::where('enhancer', $ids)
                                                            ->where('status', '1')
                                                            ->count();
                                                        // dd($countmasuk);
                                                    @endphp
                                                    {{ $countpulang }}
                                                </td>
                                                <td>
                                                    @php
                                                        $lebihawal = \App\Models\Attendance::where('enhancer', $ids)
                                                            ->where('status', '1')
                                                            ->whereTime('created_at', '<', '16:00:00')
                                                            ->count();
                                                    @endphp
                                                    {{ $lebihawal }}
                                                </td>
                                                <td>@php
                                                    $terlambat = \App\Models\Attendance::where('enhancer', $ids)
                                                        ->where('status', '0')
                                                        ->whereTime('created_at', '>', '08:00:00')
                                                        ->count();
                                                @endphp
                                                    {{ $terlambat }}
                                                </td>
                                                <td></td>
                                                <td>@php
                                                    $cuti = \App\Models\Leave::where('enhancer', $ids)
                                                        ->where('status', '0')
                                                        ->count();
                                                @endphp
                                                    {{ $cuti }}</td>
                                            </tr>
                                        @endforeach --}}
                                        @foreach ($calon as $index => $pegawai)
                                            @php
                                                $ids = $pegawai->id;
                                                $name = $pegawai->name;

                                                $countMasuk = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->where('status', '0')
                                                    ->count();

                                                $countPulang = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->where('status', '1')
                                                    ->count();

                                                $lebihAwal = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->where('status', '1')
                                                    ->whereTime('created_at', '<', '16:00:00')
                                                    ->count();

                                                $terlambat = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->where('status', '0')
                                                    ->whereTime('created_at', '>', '08:00:00')
                                                    ->count();

                                                $cuti = \App\Models\Leave::where('enhancer', $ids)
                                                    ->where('status', '0')
                                                    ->count();
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $name }}</td>
                                                <td>{{ $countMasuk }}</td>
                                                <td>{{ $countPulang }}</td>
                                                <td>{{ $lebihAwal }}</td>
                                                <td>{{ $terlambat }}</td>
                                                <td>0</td> {{-- Default tidak hadir --}}
                                                <td>{{ $cuti }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                </div>
            </div>

            {{-- Rank Kehadiran --}}
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Rank Kehadiran</h3>
                    </div>
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->

            <div class="nk-block nk-block-lg">
                <div class="card card-bordered card-preview">
                    <div class="card-inner text-center">
                        <div class="row align-items-center">
                            <!-- Rank 2 -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light text-primary">
                                        <h5 class="mb-0">🥈 Rank 2</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($secondUser->avatar)
                                            <img src="{{ asset($secondUser->avatar) }}" alt="Employee Image"
                                                class="img-fluid rounded-circle mb-3"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <div class="img-placeholder rounded-circle mb-3"></div>
                                        @endif
                                        <h6 class="font-weight-bold">{{ $secondUser->name }}</h6>
                                        <p class="mb-0 text-muted">{{ $secondUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Rank 1 -->
                            <div class="col-lg-4 col-md-12 mb-3">
                                <div class="card shadow-lg border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h4 class="mb-0">🥇 Rank 1</h4>
                                    </div>
                                    <div class="card-body">
                                        <img src="{{ asset($topUser->avatar) }}" alt="Employee Image"
                                            class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px;">
                                        <h5 class="font-weight-bold">{{ $topUser->name }}</h5>
                                        <p class="mb-0 text-muted">{{ $topUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Rank 3 -->
                            <div class="col-lg-4 c  ol-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light text-primary">
                                        <h5 class="mb-0">🥉 Rank 3</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($thirdUser->avatar)
                                            <img src="{{ asset($thirdUser->avatar) }}" alt="Employee Image"
                                                class="img-fluid rounded-circle mb-3"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <div class="img-placeholder rounded-circle mb-3"></div>
                                        @endif
                                        <h6 class="font-weight-bold">{{ $thirdUser->name }}</h6>
                                        <p class="mb-0 text-muted">{{ $thirdUser->email }}</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div><!-- .card-preview -->
            </div>


            {{-- Sanksi --}}
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Sanksi</h3>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    {{-- <li><a href="{{ route('admin.print-cetakrekapitulasi') }}" class="btn btn-secondary"
                                            target="_blank"><em class="icon ni ni-printer"></em><span>Cetak</span></a>
                                    </li> --}}
                                </ul>
                            </div>
                        </div><!-- .toggle-wrap -->
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
            <div class="nk-block nk-block-lg">
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Total Terlambat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usersWithLateCount as $ul)
                                    <!-- Gunakan $pegawai -->
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @php
                                                $name = \App\Models\User::where('id', $ul['user_id'])->value('name');
                                                // dd($name);
                                            @endphp
                                            {{ $name }}
                                        </td>
                                        <td>{{ $ul['late_count'] }}</td>
                                        <td>
                                            <a href="{{ route('admin.kelolakehadiranpegawai.send', $ul['user_id']) }}"
                                                class="btn btn-danger">
                                                Kirim Peringatan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div>
        </div>
    </div>
    <!-- Script untuk Tombol Peringatan -->
    <script>
        function sendWarning(employeeName) {
            alert("Peringatan telah dikirim ke " + employeeName);
            // Anda bisa menambahkan logika untuk mengirim notifikasi
        }
    </script>
@endsection
