@extends('layout.main')
@section('title')
    Kehadiran Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Kehadiran Pegawai</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">

                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="#" class="btn btn-secondary" target="_blank"
                                                data-bs-toggle="modal" data-bs-target="#filterModal"><em
                                                    class="icon ni ni-filter"></em><span>Filter</span></a>
                                        </li>
                                        <li><a href="#" class="btn btn-secondary" target="_blank"
                                                data-bs-toggle="modal" data-bs-target="#validasiModal"><em
                                                    class="icon ni ni-clipboad-check"></em><span>Validasi</span></a>
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-secondary" target="_blank"
                                                data-bs-toggle="modal" data-bs-target="#printModal">
                                                <em class="icon ni ni-printer"></em><span>Cetak</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <table class="datatable-init table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pegawai</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Lebih Awal</th>
                                        <th>Terlambat</th>
                                        {{-- <th>Koordinat</th> --}}
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        @php
                                            $orang = \App\Models\User::where('id', $attendance->enhancer)->value(
                                                'schedule',
                                            );
                                            $jadwal = \App\Models\Schedule::where('id', $orang)->value('id');
                                            $jadwal_detail = \App\Models\ScheduleDayM::where(
                                                'schedule_id',
                                                $jadwal,
                                            )->get();

                                            // Mendapatkan nama hari dari attendance date dalam bahasa Indonesia
                                            $attendanceDay = \Carbon\Carbon::parse($attendance->date)->locale('id')
                                                ->dayName;

                                            // Mencari jadwal yang sesuai dengan hari presensi
                                            $jadwalForDay = $jadwal_detail->firstWhere('days', $attendanceDay);

                                            // Inisialisasi variabel untuk telat dan lebih awal
                                            $lateMinutes = 0;
                                            $earlyMinutes = 0;
                                            $isLate = false;
                                            $isEarly = false;

                                            // Ambil jam clock in dan clock out jika ada
                                            $clockIn = $jadwalForDay ? $jadwalForDay->clock_in : null;
                                            $clockOut = $jadwalForDay ? $jadwalForDay->clock_out : null;

                                            $absenTime = \Carbon\Carbon::parse($attendance->time)->setDate(1970, 1, 1);
                                            $clockIn = $jadwalForDay
                                                ? \Carbon\Carbon::createFromFormat(
                                                    'H:i:s',
                                                    $jadwalForDay->clock_in,
                                                )->setDate(1970, 1, 1)
                                                : null;

                                            if ($attendance->status == 0 && $clockIn) {
                                                if ($absenTime->greaterThan($clockIn)) {
                                                    $isLate = true;
                                                    $lateMinutes = round(abs($absenTime->diffInMinutes($clockIn))); // Membulatkan
                                                } elseif ($absenTime->lessThan($clockIn)) {
                                                    $isEarly = true;
                                                    $earlyMinutes = round(abs($clockIn->diffInMinutes($absenTime))); // Membulatkan
                                                }
                                            }

                                            $clockOut = $jadwalForDay
                                                ? \Carbon\Carbon::createFromFormat(
                                                    'H:i:s',
                                                    $jadwalForDay->clock_out,
                                                )->setDate(1970, 1, 1)
                                                : null;

                                            // Jika status adalah 1 (pulang)
                                            if ($attendance->status == 1 && $clockOut) {
                                                if ($absenTime->greaterThan($clockOut)) {
                                                    $isLate = true;
                                                    $lateMinutes = round(abs($absenTime->diffInMinutes($clockOut))); // Membulatkan
                                                } elseif ($absenTime->lessThan($clockOut)) {
                                                    $isEarly = true;
                                                    $earlyMinutes = round(abs($clockOut->diffInMinutes($absenTime))); // Membulatkan
                                                }
                                            }
                                        @endphp


                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attendance->user->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                            <td>{{ $absenTime->format('H:i') }}</td>

                                            {{-- Menampilkan status Masuk atau Pulang --}}
                                            <td>
                                                @if ($attendance->status == 0)
                                                    <span class="badge bg-success">Masuk</span>
                                                @elseif ($attendance->status == 1)
                                                    <span class="badge bg-danger">Pulang</span>
                                                @endif
                                            </td>

                                            {{-- Kolom Lebih Awal --}}
                                            <td>
                                                @if ($isEarly)
                                                    <span> {{ $earlyMinutes }} menit</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Kolom Terlambat --}}
                                            <td>
                                                @if ($isLate)
                                                    <span>{{ $lateMinutes }} menit</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <ul class="nk-tb-actions gx-2">
                                                    <li>
                                                        <div class="dropdown">
                                                            <a href="#"
                                                                class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                                data-bs-toggle="dropdown">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    @if ($attendance->status == 0)
                                                                        <li>
                                                                            <a href="{{ route('admin.print-kelolakehadiranpegawai-masuk', ['id' => $attendance->id]) }}"
                                                                                target="_blank" class="dropdown-item">
                                                                                <em class="icon ni ni-printer"></em> Cetak
                                                                                Masuk
                                                                            </a>
                                                                        </li>
                                                                    @elseif ($attendance->status == 1)
                                                                        <li>
                                                                            <a href="{{ route('admin.print-kelolakehadiranpegawai-keluar', ['id' => $attendance->id]) }}"
                                                                                target="_blank" class="dropdown-item">
                                                                                <em class="icon ni ni-printer"></em> Cetak
                                                                                Pulang
                                                                            </a>
                                                                        </li>
                                                                    @endif

                                                                    <li>
                                                                        <a href="#"><em
                                                                                class="icon ni ni-trash"></em><span>Hapus</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>



                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
                <!-- Modal Cetak -->
                <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="printModalLabel">Cetak Kehadiran Pegawai</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="printForm" action="{{ route('admin.print-kelolakehadiranpegawai') }}" target="_blank"
                                method="GET">
                                <div class="modal-body">
                                    <!-- Dropdown Pilihan Cetak -->
                                    <div class="form-group">
                                        <label for="printOption">Pilih Opsi Cetak</label>
                                        <select name="printOption" id="printOption"
                                            class="form-select js-select2 select2-hidden-accesible valid"
                                            onchange="toggleDateInputs()">
                                            <option value="all">Cetak Semua</option>
                                            <option value="byDate">Cetak Sesuai Tanggal</option>
                                            <option value="byMonth">Cetak Sesuai Bulan</option>
                                            <option value="byYear">Cetak Sesuai Tahun</option>
                                        </select>
                                    </div>

                                    <!-- Input Tanggal -->
                                    <div class="form-group" id="dateInput" style="display:none;">
                                        <label class="form-label">Tanggal</label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right">
                                                <em class="icon ni ni-calendar-alt"></em>
                                            </div>
                                            <input type="text" data-date-format="dd M yyyy"
                                                class="form-control date-picker" name="date">
                                        </div>
                                    </div>

                                    <!-- Input Bulan -->
                                    <div class="form-group" id="monthInput" style="display:none;">
                                        <label class="form-label">Bulan</label>
                                        <div class="form-control-wrap">
                                            <input type="month" class="form-control" name="month">
                                        </div>
                                    </div>

                                    <!-- Input Tahun -->
                                    <div class="form-group" id="yearInput" style="display:none;">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control-wrap">
                                            <input type="number" class="form-control" name="year"
                                                placeholder="Tahun" min="1900" max="{{ now()->year }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Cetak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Filter -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Kehadiran Pegawai</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="filterForm" action="{{ route('admin.filterKehadiran') }}" method="GET">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal</label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right">
                                                <em class="icon ni ni-calendar-alt"></em>
                                            </div>
                                            <input type="text" data-date-format="dd M yyyy"
                                                class="form-control date-picker" id="date" name="date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- validasi modal --}}
                <div class="modal fade" id="validasiModal" tabindex="-1" aria-labelledby="validasiModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="validasiModalLabel">Validasi Kehadiran Pegawai</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="filterForm" action="{{ route('admin.filterKehadiran') }}" method="GET">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Nama Pegawai</label>
                                        <div class="form-control-wrap">
                                            <select name="status" id="status1"
                                                class="form-select js-select2 select2-hidden-accesible valid">
                                                @foreach ($attendances as $item)
                                                    <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Status Kehadiran</label>
                                        <div class="form-control-wrap">
                                            <select name="status" id="status1"
                                                class="form-select js-select2 select2-hidden-accesible valid">
                                                <option value="Semua">Masuk</option>
                                                <option value="Tepat Waktu">Pulang</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#status1').select2();
            $('#status2').select2(); // Inisialisasi Select2 pada elemen dengan id "status"
            $('#printOption').select2(); // Inisialisasi Select2 pada elemen dengan id "printOption"
        });

        // Fungsi untuk menampilkan input berdasarkan opsi cetak
        // Fungsi untuk menampilkan input yang sesuai dengan pilihan opsi cetak
        function toggleDateInputs() {
            var printOption = document.getElementById('printOption').value;

            // Sembunyikan semua input
            document.getElementById('dateInput').style.display = 'none';
            document.getElementById('monthInput').style.display = 'none';
            document.getElementById('yearInput').style.display = 'none';

            // Tampilkan input yang sesuai dengan pilihan
            if (printOption === 'byDate') {
                document.getElementById('dateInput').style.display = 'block';
            } else if (printOption === 'byMonth') {
                document.getElementById('monthInput').style.display = 'block';
            } else if (printOption === 'byYear') {
                document.getElementById('yearInput').style.display = 'block';
            }
        }
    </script>
@endsection
