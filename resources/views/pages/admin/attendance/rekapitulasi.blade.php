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
                                <h3 class="nk-block-title page-title">Rekapitulasi</h3>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            {{-- <li><a href="{{ route('admin.print-cetakrekapitulasi') }}"
                                                    class="btn btn-secondary" target="_blank"><em
                                                        class="icon ni ni-printer"></em><span>Cetak</span></a></li> --}}
                                            <!-- Button to open the modal for selecting month and year -->
                                            <li>
                                                <button class="btn btn-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#selectMonthYearModal">
                                                    <em class="icon ni ni-printer"></em><span>Cetak</span>
                                                </button>
                                            </li>
                                        </ul>

                                        <!-- Modal for selecting month and year -->
                                        <div class="modal fade" id="selectMonthYearModal" tabindex="-1"
                                            aria-labelledby="selectMonthYearModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content p-3">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="selectMonthYearModalLabel">Pilih Bulan
                                                            dan Tahun</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.print-cetakrekapitulasi-bulan') }}"
                                                            method="GET" target="_blank">
                                                            @csrf
                                                            <!-- Month and Year Selection -->
                                                            <div class="form-group mb-3">
                                                                <label for="month">Bulan</label>
                                                                <select name="month" id="month" class="form-control"
                                                                    required>
                                                                    <option value="" selected disabled>-- Pilih Bulan
                                                                        --</option>
                                                                    <option value="1">Januari</option>
                                                                    <option value="2">Februari</option>
                                                                    <option value="3">Maret</option>
                                                                    <option value="4">April</option>
                                                                    <option value="5">Mei</option>
                                                                    <option value="6">Juni</option>
                                                                    <option value="7">Juli</option>
                                                                    <option value="8">Agustus</option>
                                                                    <option value="9">September</option>
                                                                    <option value="10">Oktober</option>
                                                                    <option value="11">November</option>
                                                                    <option value="12">Desember</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="year">Tahun</label>
                                                                <select name="year" id="year" class="form-control"
                                                                    required>
                                                                    <option value="" selected disabled>-- Pilih Tahun
                                                                        --</option>
                                                                    @for ($year = 2020; $year <= 2030; $year++)
                                                                        <option value="{{ $year }}">
                                                                            {{ $year }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <!-- Submit Button -->
                                                            <div class="modal-footer p-0 d-flex justify-content-between">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Cetak</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                        <h3 class="nk-block-title page-title">Daftar Pegawai Bermasalah</h3>
                    </div>
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->

            <div class="nk-block nk-block-lg">
                
                        <div class="row">
                            <!-- Ranking Terlambat (kiri) -->
                            <div class="col-lg-6">
                                <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                <h4 class="mb-3">Masuk Terlambat</h4>
                                <div class="row align-items-center">
                                    <!-- Rank 2 Terlambat -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light text-primary">
                                                <h6 class="mb-0">🥈 Rank 2</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $secondUser->name }}</p>
                                                <p class="mb-0 text-muted">{{ $secondUser->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rank 1 Terlambat -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-lg border-secondary">
                                            <div class="card-header bg-secondary text-white">
                                                <h5 class="mb-0">🥇 Rank 1</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $topUser->name }}</p>
                                                <p class="mb-0 text-muted">{{ $topUser->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rank 3 Terlambat -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light text-primary">
                                                <h6 class="mb-0">🥉 Rank 3</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $thirdUser->name }}</p>
                                                <p class="mb-0 text-muted">{{ $thirdUser->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>

                            @php
                                // Ubah koleksi ke collection jika belum
                                $sortedResults = collect($result)->sortByDesc('absent_count')->values();

                                $topAbsentUser = (object) ($sortedResults[0] ?? []);
                                $secondAbsentUser = (object) ($sortedResults[1] ?? []);
                                $thirdAbsentUser = (object) ($sortedResults[2] ?? []);

                                $name1 = \App\Models\User::find($topAbsentUser->user_id);
                                $name2 = \App\Models\User::find($secondAbsentUser->user_id);
                                $name3 = \App\Models\User::find($thirdAbsentUser->user_id);
                                // dd($name1);
                            @endphp


                            <!-- Ranking Tidak Masuk (kanan) -->
                            <div class="col-lg-6">
                                <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                <h4 class="mb-3">Tidak Masuk</h4>
                                <div class="row align-items-center">
                                    <!-- Rank 2 Tidak Masuk -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light text-primary">
                                                <h6 class="mb-0">🥈 Rank 2</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $name2->name }}</p>
                                                <p class="mb-0 text-muted">{{ $name2->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rank 1 Tidak Masuk -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-lg border-secondary">
                                            <div class="card-header bg-secondary text-white">
                                                <h5 class="mb-0">🥇 Rank 1</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $name1->name }}</p>
                                                <p class="mb-0 text-muted">{{ $name1->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rank 3 Tidak Masuk -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light text-primary">
                                                <h6 class="mb-0">🥉 Rank 3</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="font-weight-bold">{{ $name3->name }}</p>
                                                <p class="mb-0 text-muted">{{ $name3->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                </div>
                            </div> <!-- end col kanan -->
                        </div>
                    {{-- </div> --}}
                {{-- </div><!-- .card-preview --> --}}
            </div>




            {{-- Sanksi --}}
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Peringatan Sanksi</h3>
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
                                    <th>Total Terlambat Masuk</th>
                                    <th>Total Tidak Masuk</th>
                                    <th rowspan="
                                    2">Peringatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $ul)
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
                                        <td>{{ $ul['absent_count'] }}</td>
                                       <form action="{{ route('admin.kelolakehadiranpegawai.send', ['id' => $ul['user_id']]) }}" method="POST">
                                            @csrf
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @php
                                                        $peringatan = \App\Models\PeringatanM::where('user_id', $ul['user_id'])->orderBy('created_at','desc')->first();
                                                        $status = $peringatan->status ?? 0; // default 0 jika belum ada
                                                    @endphp

                                                    <input type="hidden" name="totalDays" value="{{ $ul['late_count'] }}">

                                                    @foreach ([0, 1, 2, 3] as $sp)
                                                        <div class="form-check text-center flex-fill">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="sp"
                                                                id="sp_{{ $ul['user_id'] }}_{{ $sp }}"
                                                                value="{{ $sp }}"
                                                                {{ $status >= $sp ? 'checked disabled' : '' }}
                                                            >
                                                            <label class="btn btn-sm w-100 {{ $status >= $sp ? 'btn-success' : 'btn-outline-dark' }}"
                                                                for="sp_{{ $ul['user_id'] }}_{{ $sp }}">
                                                                SP {{ $sp }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>

                                            <td>
                                                <button type="submit" class="btn btn-primary">Kirim Peringatan</button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div>

            
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.sp-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const sp = this.dataset.sp;
                const userId = this.dataset.user;
                const url = `/admin/kelolakehadiranpegawai/send?id=${userId}&sp=${sp}`;
                window.location.href = url;
            });
        });
    });
</script>

    <!-- Script untuk Tombol Peringatan -->
    <script>
        function sendWarning(employeeName) {
            alert("Peringatan telah dikirim ke " + employeeName);
            // Anda bisa menambahkan logika untuk mengirim notifikasi
        }
    </script>


@endsection
