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
                                            <form action="{{ route('admin.rekapitulasi') }}" method="GET"
                                                class="d-flex align-items-end ">
                                                <li class="d-flex align-items-center gap-2">

                                                    <label for="start_date" class="mb-0 me-1">Dari :</label>
                                                    <input type="date" id="start_date" name="start_date"
                                                        value="{{ $request->start_date }}"
                                                        class="form-control form-control-sm" style="width: 140px;" />

                                                    <label for="end_date" class="mb-0 ms-2 me-1">Sampai :</label>
                                                    <input type="date" id="end_date" name="end_date"
                                                        value="{{ $request->end_date }}"
                                                        class="form-control form-control-sm" style="width: 140px;" />

                                                    <button type="submit" class="btn btn-sm btn-secondary ms-3">
                                                        <em class="icon ni ni-filter"></em> Filter
                                                    </button>

                                            </form>
                                            </li>


                                            <li>
                                                <button class="btn btn-sm btn-secondary ms-3" data-bs-toggle="modal"
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
                                <table id="dataTable" class="datatable-init table">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Created At</th>
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
                                        @foreach ($calon as $index => $pegawai)
                                            @php
                                                $ids = $pegawai->id;
                                                $scheduleId = $pegawai->schedule;

                                                $countMasuk = 0;
                                                $countPulang = 0;
                                                $lebihAwal = 0;
                                                $terlambat = 0;
                                                $tidakHadir = 0;

                                                // Hitung jumlah cuti
                                                $cuti = \App\Models\Leave::where('enhancer', $ids)->where(
                                                    'status',
                                                    '0',
                                                );

                                                if (request()->start_date || request()->end_date) {
                                                    $cuti->whereBetween('created_at', [
                                                        \Carbon\Carbon::parse(request()->start_date)->startOfDay(),
                                                        \Carbon\Carbon::parse(request()->end_date)->endOfDay(),
                                                    ]);
                                                }

                                                $cuti = $cuti->count();

                                                // Ambil jadwal
                                                if ($scheduleId) {
                                                    $schedule = \App\Models\Schedule::find($scheduleId);
                                                    $scheduledays = $schedule
                                                        ? \App\Models\ScheduleDayM::where(
                                                            'schedule_id',
                                                            $schedule->id,
                                                        )->get()
                                                        : collect();
                                                } else {
                                                    $scheduledays = collect();
                                                }

                                                // Ambil absensi pegawai
                                                $attendancesQuery = \App\Models\Attendance::where('enhancer', $ids);

                                                if (request()->start_date && request()->end_date) {
                                                    $attendancesQuery->whereBetween('created_at', [
                                                        \Carbon\Carbon::parse(request()->start_date)->startOfDay(),
                                                        \Carbon\Carbon::parse(request()->end_date)->endOfDay(),
                                                    ]);
                                                }

                                                $attendances = $attendancesQuery->get();

                                                // Group berdasarkan tanggal
                                                $attendancesByDate = $attendances->groupBy(function ($item) {
                                                    return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
                                                });

                                                // Tentukan rentang tanggal kerja (dari absensi pertama hingga hari ini)
                                                $firstAttendance = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->orderBy('created_at')
                                                    ->first();
                                                $start = $firstAttendance
                                                    ? $firstAttendance->created_at->copy()->startOfDay()
                                                    : now()->startOfMonth();
                                                $end = now()->endOfDay(); // hanya sampai hari ini

                                                // Buat array tanggal kerja berdasarkan jadwal
                                                $workDays = [];
                                                while ($start <= $end) {
                                                    $dayName = $start->locale('id')->dayName;
                                                    if ($scheduledays->where('days', $dayName)->count()) {
                                                        $workDays[] = $start->format('Y-m-d');
                                                    }
                                                    $start->addDay();
                                                }

                                                // Proses absensi
                                                foreach ($attendancesByDate as $date => $records) {
                                                    $masuk = $records
                                                        ->where('status', '0')
                                                        ->sortBy('created_at')
                                                        ->first();
                                                    $pulang = $records
                                                        ->where('status', '1')
                                                        ->sortByDesc('created_at')
                                                        ->first();

                                                    $dayName = \Carbon\Carbon::parse($date)->locale('id')->dayName;
                                                    $scheduleDay = $scheduledays->firstWhere('days', $dayName);

                                                    if (!$scheduleDay) {
                                                        continue;
                                                    }

                                                    if ($masuk) {
                                                        $countMasuk++;
                                                        $jamMasuk = \Carbon\Carbon::parse($masuk->created_at)->format(
                                                            'H:i:s',
                                                        );
                                                        if ($jamMasuk > $scheduleDay->clock_in) {
                                                            $terlambat++;
                                                        }
                                                    }

                                                    if ($pulang) {
                                                        $jamPulang = \Carbon\Carbon::parse($pulang->created_at)->format(
                                                            'H:i:s',
                                                        );
                                                        if ($jamPulang < $scheduleDay->clock_out) {
                                                            $lebihAwal++;
                                                        } else {
                                                            $countPulang++;
                                                        }
                                                    }
                                                }

                                                // Hitung total hari kerja dan ketidakhadiran
                                                $totalHariKerja = count($workDays);
                                                $tidakHadir = $totalHariKerja - $countMasuk - $cuti;

                                                // Ambil tanggal absen terakhir
                                                $createdAt = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->latest()
                                                    ->value('created_at');
                                            @endphp


                                            <tr>
                                                <td class="d-none created-at">{{ $createdAt }}</td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pegawai->name }}</td>
                                                <td id="countMasuk">{{ $countMasuk }}</td>
                                                <td id="countPulang">{{ $countPulang }}</td>
                                                <td id="lebihAwal">{{ $lebihAwal }}</td>
                                                <td id="terlambat">{{ $terlambat }}</td>
                                                <td id="tidakHadir">{{ $tidakHadir < 0 ? 0 : $tidakHadir }}</td>
                                                <td id="cuti">{{ $cuti }}</td>
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
                                                <p class="font-weight-bold">{{ $secondUser->name ?? null }}</p>
                                                <p class="mb-0 text-muted">{{ $secondUser->email ?? null }}</p>
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
                                                <p class="font-weight-bold">{{ $thirdUser->name ?? null }}</p>
                                                <p class="mb-0 text-muted">{{ $thirdUser->email ?? null }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $sortedResults = collect($result)->sortByDesc('absent_count')->values();

                        $topAbsentUser = (object) ($sortedResults[0] ?? []);
                        $secondAbsentUser = (object) ($sortedResults[1] ?? []);
                        $thirdAbsentUser = (object) ($sortedResults[2] ?? []);

                        $name1 = isset($topAbsentUser->user_id)
                            ? \App\Models\User::find($topAbsentUser->user_id)
                            : null;
                        $name2 = isset($secondAbsentUser->user_id)
                            ? \App\Models\User::find($secondAbsentUser->user_id)
                            : null;
                        $name3 = isset($thirdAbsentUser->user_id)
                            ? \App\Models\User::find($thirdAbsentUser->user_id)
                            : null;
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
                                                <p class="font-weight-bold">{{ $name2->name ?? null }}</p>
                                                <p class="mb-0 text-muted">{{ $name2->email ?? null }}</p>
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
                                                <p class="font-weight-bold">{{ $name3->name ?? null }}</p>
                                                <p class="mb-0 text-muted">{{ $name3->email ?? null }}</p>
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
                                    10">Peringatan</th>
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
                                        <form
                                            action="{{ route('admin.kelolakehadiranpegawai.send', ['id' => $ul['user_id']]) }}"
                                            method="POST">
                                            @csrf
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @php
                                                        $peringatan = \App\Models\PeringatanM::where(
                                                            'user_id',
                                                            $ul['user_id'],
                                                        )
                                                            ->orderBy('created_at', 'desc')
                                                            ->first();
                                                        $status = $peringatan->status ?? -1; // default -1 supaya sp0 bisa default hijau dan disabled
                                                    @endphp

                                                    <input type="hidden" name="totalDays"
                                                        value="{{ $ul['late_count'] }}">

                                                    @foreach ([0, 1, 2, 3] as $sp)
                                                        @php
                                                            $colors = [
                                                                0 => 'btn-success', // hijau
                                                                1 => 'btn-warning', // kuning
                                                                2 => 'btn-orange', // orange
                                                                3 => 'btn-danger', // merah
                                                            ];

                                                            $isDisabled = $status >= $sp || $sp === 0; // SP0 selalu di-disable
                                                            $btnClass = $isDisabled
                                                                ? $colors[$sp]
                                                                : 'btn-outline-secondary';
                                                        @endphp

                                                        <input type="checkbox" class="btn-check sp-checkbox"
                                                            name="sp"
                                                            id="sp_{{ $ul['user_id'] }}_{{ $sp }}"
                                                            value="{{ $sp }}" autocomplete="off"
                                                            {{ $isDisabled ? 'checked disabled' : '' }}>
                                                        <label class="btn btn-sm flex-fill {{ $btnClass }}"
                                                            for="sp_{{ $ul['user_id'] }}_{{ $sp }}"
                                                            data-sp="{{ $sp }}">
                                                            SP {{ $sp }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>

                                            <td>
                                                <button type="submit" class="btn btn-secondary">Kirim Peringatan</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.sp-checkbox');

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const [_, userId, sp] = this.id.split(
                        '_'); // ambil user_id dan sp dari ID checkbox
                    const spValue = parseInt(sp);
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    const colors = ['btn-success', 'btn-warning', 'btn-orange', 'btn-danger'];

                    if (spValue === 0) return; // SP0 tidak boleh diubah

                    // Ambil semua SP checkbox untuk user ini
                    const userCheckboxes = Array.from(document.querySelectorAll(
                        `.sp-checkbox[id^="sp_${userId}_"]`));

                    const prevCheckbox = document.getElementById(`sp_${userId}_${spValue - 1}`);
                    const nextCheckbox = document.getElementById(`sp_${userId}_${spValue + 1}`);

                    // Cegah centang loncat (tanpa alert)
                    if (this.checked && (!prevCheckbox || !prevCheckbox.checked)) {
                        this.checked = false;
                        return;
                    }

                    // Jika uncheck, maka semua SP di atasnya ikut di-uncheck
                    if (!this.checked) {
                        userCheckboxes.forEach(cb => {
                            const val = parseInt(cb.value);
                            if (val > spValue && !cb.disabled) {
                                cb.checked = false;
                                const lbl = document.querySelector(`label[for="${cb.id}"]`);
                                lbl.classList.remove(...colors);
                                lbl.classList.add('btn-outline-secondary');
                            }
                        });
                    }

                    // Update warna tombol
                    label.classList.remove(...colors, 'btn-outline-secondary');
                    if (this.checked) {
                        label.classList.add(colors[spValue]);
                    } else {
                        label.classList.add('btn-outline-secondary');
                    }
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

    <style>
        /* Global Styling */
        .btn-success,
        .btn-warning,
        .btn-orange,
        .btn-danger,
        .btn-outline-secondary {
            height: 50px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* === SP0 - Hijau === */
        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }

        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background-color: #198754 !important;
            color: #fff !important;
            /* Tetap hijau */
            border-color: #198754 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* === SP1 - Kuning === */
        .btn-warning {
            color: #212529;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active {
            background-color: #ffc107 !important;
            color: #212529 !important;
            /* Tetap kuning */
            border-color: #ffc107 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* === SP2 - Orange === */
        .btn-orange {
            color: #fff;
            background-color: #fd7e14;
            border-color: #fd7e14;
        }

        .btn-orange:hover,
        .btn-orange:focus,
        .btn-orange:active {
            background-color: #fd7e14 !important;
            color: #fff !important;
            /* Tetap oranye */
            border-color: #fd7e14 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* === SP3 - Merah === */
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active {
            background-color: #dc3545 !important;
            olor: #fff !important;
            /* Tetap merah */
            border-color: #dc3545 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* === Jika tombol belum di-check === */
        .btn-outline-secondary {
            color: #6c757d;
            background-color: #ffffff;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active {
            color: #6c757d !important;
            background-color: #ffffff !important;

            /* Tetap putih */
            border-color: #6c757d !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Menghilangkan efek klik biru */
        .btn-check:focus+label {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
@endsection
