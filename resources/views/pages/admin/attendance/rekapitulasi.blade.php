@extends('layout.main')
@section('title')
    Rekapitulasi
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
                                                                    class="btn btn-secondary">Cetak</button>
                                                                <button type="button" class="btn btn-danger"
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

                                                // Ambil tanggal absensi pertama
                                                $firstAttendance = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->orderBy('created_at')
                                                    ->first();

                                                // Rentang hanya dari absensi pertama hingga hari ini
                                                $start = $firstAttendance
                                                    ? \Carbon\Carbon::parse($firstAttendance->created_at)->startOfDay()
                                                    : now()->startOfMonth();
                                                $end = now()->endOfDay(); // tidak termasuk masa depan

                                                // Buat array tanggal kerja sesuai jadwal (hanya hari kerja)
                                                // Ambil tanggal absensi pertama
                                                $firstAttendance = \App\Models\Attendance::where('enhancer', $ids)
                                                    ->orderBy('created_at')
                                                    ->first();
                                                $firstAbsenceDate = $firstAttendance
                                                    ? \Carbon\Carbon::parse($firstAttendance->created_at)->startOfDay()
                                                    : null;

                                                // Gunakan tanggal dari request atau fallback
                                                $start = request()->start_date
                                                    ? \Carbon\Carbon::parse(request()->start_date)->startOfDay()
                                                    : $firstAbsenceDate ?? now()->startOfMonth();

                                                $end = request()->end_date
                                                    ? \Carbon\Carbon::parse(request()->end_date)->endOfDay()
                                                    : now()->endOfDay();

                                                // Jika filter lebih awal dari absensi pertama, set start dari absensi pertama
                                                if ($firstAbsenceDate && $start < $firstAbsenceDate) {
                                                    $start = $firstAbsenceDate->copy();
                                                }

                                                // Buat array tanggal kerja sesuai jadwal (hanya hari kerja)
                                                $workDays = [];
                                                $current = $start->copy();
                                                while ($current <= $end) {
                                                    $dayName = $current->locale('id')->dayName;
                                                    if ($scheduledays->where('days', $dayName)->count()) {
                                                        $workDays[] = $current->format('Y-m-d');
                                                    }
                                                    $current->addDay();
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

                                                        // Hitung total pulang (semua)
                                                        $countPulang++;

                                                        // Cek jika pulangnya lebih awal dari jadwal
                                                        if ($jamPulang < $scheduleDay->clock_out) {
                                                            $lebihAwal++;
                                                        }
                                                    }
                                                }

                                                // Hitung ketidakhadiran
                                                $totalHariKerja = count($workDays);
                                                // dd($totalHariKerja);
                                                $tidakHadir = $totalHariKerja - $countMasuk - $cuti;

                                                // Tanggal terakhir absen
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
                </div>
            </div>

            <div class="nk-block nk-block-lg">

                @php
                    // Urutkan berdasarkan keterlambatan
                    $sortedLate = $result->sortByDesc('late_count')->values();

                    $topLate1 = $sortedLate[0] ?? null;
                    $topLate2 = $sortedLate[1] ?? null;
                    $topLate3 = $sortedLate[2] ?? null;

                    // Urutkan berdasarkan tidak masuk
                    $sortedAbsent = $result->sortByDesc('absent_count')->values();

                    $topAbsent1 = $sortedAbsent[0] ?? null;
                    $topAbsent2 = $sortedAbsent[1] ?? null;
                    $topAbsent3 = $sortedAbsent[2] ?? null;
                @endphp

                <div class="row">
                    {{-- Tabel Masuk Terlambat --}}
                    <div class="col-lg-6 mb-4">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <h4 class="mb-3">Pegawai Masuk Terlambat</h4>
                                <table id="dataTable" class="datatable-init table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            {{-- <th>Email</th> --}}
                                            <th>Jumlah Terlambat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($topLate1)
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $topLate1['name'] }}</td>
                                                <td>{{ $topLate1['late_count'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($topLate2)
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $topLate2['name'] }}</td>
                                                <td>{{ $topLate2['late_count'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($topLate3)
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $topLate3['name'] }}</td>
                                                <td>{{ $topLate3['late_count'] }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Tidak Masuk --}}
                    <div class="col-lg-6 mb-4">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <h4 class="mb-3">Pegawai Tidak Masuk</h4>
                                <table id="dataTable" class="datatable-init table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            {{-- <th>Email</th> --}}
                                            <th>Jumlah Tidak Masuk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($topAbsent1)
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $topAbsent1['name'] }}</td>
                                                <td>{{ $topAbsent1['absent_count'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($topAbsent2)
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $topAbsent2['name'] }}</td>
                                                <td>{{ $topAbsent2['absent_count'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($topAbsent3)
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $topAbsent3['name'] }}</td>
                                                <td>{{ $topAbsent3['absent_count'] }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- Sanksi --}}
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Peringatan Sanksi</h3>
                    </div>
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
            <div class="nk-block nk-block-lg">
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <table id="dataTable" class="datatable-init table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Total Terlambat Masuk</th>
                                    <th>Total Tidak Masuk</th>
                                    <th>Peringatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $ul)
                                    @php
                                        $name = \App\Models\User::where('id', $ul['user_id'])->value('name');
                                        $peringatan = \App\Models\PeringatanM::where('user_id', $ul['user_id'])
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $status = $peringatan->status ?? -1;
                                    @endphp
                                    <tr data-user-id="{{ $ul['user_id'] }}" data-late="{{ $ul['late_count'] }}"
                                        data-absent="{{ $ul['absent_count'] }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="pegawai-nama">{{ $name }}</td>
                                        <td class="late-count">{{ $ul['late_count'] }}</td>
                                        <td class="absent-count">{{ $ul['absent_count'] }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @foreach ([0, 1, 2, 3] as $sp)
                                                    @php
                                                        $colors = [
                                                            0 => 'btn-success',
                                                            1 => 'btn-warning',
                                                            2 => 'btn-orange',
                                                            3 => 'btn-danger',
                                                        ];
                                                        $isDisabled = $status >= $sp || $sp === 0;
                                                        $btnClass = $isDisabled
                                                            ? $colors[$sp]
                                                            : 'btn-outline-secondary';
                                                    @endphp

                                                    <form
                                                        action="{{ route('admin.kelolakehadiranpegawai.send', ['id' => $ul['user_id']]) }}"
                                                        method="POST" class="sp-form d-inline">
                                                        @csrf
                                                        <input type="hidden" name="totalDays"
                                                            value="{{ $ul['late_count'] }}">
                                                        <input type="hidden" name="sp"
                                                            value="{{ $sp }}">
                                                        <input type="checkbox" class="btn-check sp-checkbox"
                                                            id="sp_{{ $ul['user_id'] }}_{{ $sp }}"
                                                            data-user="{{ $ul['user_id'] }}"
                                                            data-sp="{{ $sp }}"
                                                            {{ $isDisabled ? 'checked disabled' : '' }}>
                                                        <label class="btn btn-sm {{ $btnClass }}"
                                                            for="sp_{{ $ul['user_id'] }}_{{ $sp }}">SP
                                                            {{ $sp }}</label>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('admin.kelolakehadiranpegawai.send', ['id' => $ul['user_id']]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="totalDays" value="{{ $ul['late_count'] }}">
                                                <button type="submit" class="btn btn-secondary">Kirim
                                                    Peringatan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const rows = document.querySelectorAll('tr[data-user-id]');

                    rows.forEach(row => {
                        try {
                            const userId = row.dataset.userId;
                            const late = parseInt(row.dataset.late || '0');
                            const absent = parseInt(row.dataset.absent || '0');
                            const total = late + absent;

                            let maxSP = -1;
                            if (total >= 6) maxSP = 3;
                            else if (total >= 4) maxSP = 2;
                            else if (total >= 2) maxSP = 1;

                            if (maxSP > 0) {
                                console.log(`🔍 Cek user ${userId}, total: ${total}, target SP: ${maxSP}`);

                                for (let sp = 1; sp <= maxSP; sp++) {
                                    const checkbox = row.querySelector(`#sp_${userId}_${sp}`);
                                    if (checkbox && !checkbox.disabled) {
                                        checkbox.checked = true;
                                        const form = checkbox.closest('form');
                                        setTimeout(() => {
                                            console.log(`✅ Submit SP ${sp} untuk user ID: ${userId}`);
                                            form.submit();
                                        }, 100);
                                        break; // hanya kirim 1 SP per load
                                    } else {
                                        console.log(
                                            `⚠️ SP ${sp} untuk user ID ${userId} sudah dikirim atau checkbox tidak ditemukan`
                                        );
                                    }
                                }
                            }
                        } catch (err) {
                            console.error(`❌ Error SP otomatis user ${userId}:`, err);
                        }
                    });
                });
            </script>


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
