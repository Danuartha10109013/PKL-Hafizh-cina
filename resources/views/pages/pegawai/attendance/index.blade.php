@extends('layout.pegawai.main')

@section('title')
    Absensi {{ auth()->user()->name }}
@endsection

@section('content-pegawai')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <!-- Pesan Peringatan jika sudah melebihi waktu absensi -->
                    <div id="message" style="display: none;">
                        <div class="alert alert-pro alert-danger">
                            <div class="alert-text">
                                <strong>Perhatian!</strong> Sudah melewati waktu absensi masuk.
                            </div>
                        </div>
                    </div>
                    @php
                        $acuan = Auth::user()->id;
                        $acuans = \App\Models\User::where('id', $acuan)->value('acuan');
                        // dd($acuans);
                    @endphp
                    @if ($acuans == null)
                        <div class="alert alert-warning text-center p-4 rounded">
                            <p class="mb-3">Silakan masukkan gambar untuk acuan terlebih dahulu sebelum melakukan absensi
                            </p>
                            <p>
                                contoh gambar yang baik
                            </p>
                            <img class="mb-3" width="20%" src="{{ asset('acuan.jpg') }}" alt="">
                            <form action="{{ route('pegawai.attendance-setup') }}" method="POST"
                                class="d-flex flex-column align-items-center" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                <input type="file" name="acuan" class="form-control mb-3" required>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>

                        </div>
                    @endif
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <!-- Tombol Absen Masuk/Pulang -->
                            @php
                                $show = \App\Models\ShowM::find(1);
                                $now = \Carbon\Carbon::now();
                                $today = $now->toDateString();
                                $dayName = $now->locale('id')->dayName;

                                // Jika show == 1, tampilkan tombol absen langsung tanpa cek waktu
                                if ($show && $show->show == 1) {
                                    echo '<li class="mb-3"><a id="attendance-btn" href="' . route('pegawai.tambah-attendance') . '?status=in" class="btn btn-secondary d-inline-block setup-cek">Absen</a></li>';
                                } else {
                                    // Jika show == 0 atau tidak ditemukan, gunakan aturan jadwal
                                    if (!in_array($dayName, ['Sabtu', 'Minggu'])) {
                                        $jadwalHariIni = $jadwal_detail->where('days', $dayName)->first();

                                        if ($jadwalHariIni) {
                                            $clockIn = \Carbon\Carbon::parse($jadwalHariIni->clock_in);
                                            $clockOut = \Carbon\Carbon::parse($jadwalHariIni->clock_out);

                                            $absenMasukStart = $clockIn->copy()->subHour();
                                            $absenMasukEnd = $clockIn->copy()->addHour();

                                            $absenPulangStart = $clockOut->copy()->subMinutes(15);
                                            $absenPulangEnd = $clockOut->copy()->addHour(2);

                                            $sudahAbsenMasuk = $attendances->where('date', $today)->where('status', 'in')->count() > 0;
                                            $sudahAbsenPulang = $attendances->where('date', $today)->where('status', 'out')->count() > 0;

                                            if (!$sudahAbsenMasuk && $now->between($absenMasukStart, $absenMasukEnd)) {
                                                echo '<li><a id="attendance-btn" href="' . route('pegawai.tambah-attendance') . '?status=in" class="btn btn-secondary d-inline-block setup-cek" onclick="checkLate()">Absen Masuk</a></li>';
                                            }

                                            if (!$sudahAbsenPulang && $sudahAbsenMasuk && $now->between($absenPulangStart, $absenPulangEnd)) {
                                                echo '<li><a id="attendance-btn" href="' . route('pegawai.tambah-attendance') . '?status=out" class="btn btn-secondary d-inline-block setup-cek">Absen Pulang</a></li>';
                                            }
                                        }
                                    }
                                }

                            @endphp

                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                let acuans = @json($acuans); // Konversi PHP ke JavaScript dengan aman
                                let buttons = document.querySelectorAll(".setup-cek");

                                if (!acuans || acuans === "null") {
                                    buttons.forEach(button => {
                                        button.style.pointerEvents = "none"; // Menonaktifkan klik
                                        button.style.opacity = "0.5"; // Membuat tombol terlihat nonaktif
                                    });
                                }
                            });
                        </script>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <ul class="nk-block-tools g-3">
                                    <!-- Tombol Cetak -->
                                    <li>
                                        <a href="#" class="btn btn-secondary d-inline-block" data-bs-toggle="modal"
                                            data-bs-target="#printModal" target="_blank">
                                            <em class="icon ni ni-printer"></em> Cetak
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Pesan Sukses/Error -->
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


                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Kehadiran -->
            {{-- <div class="nk-block nk-block-lg">
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <h4 class="card-title text-center mt-1">Absensi Anda</h4>
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Koordinat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</td>
                                        <td>
                                            @if ($attendance->status == 0)
                                                Masuk
                                            @else
                                                Pulang
                                            @endif
                                        </td>
                                        <td>{{ $attendance->coordinate }}</td>
                                        <td>
                                            <a href="{{ route('pegawai.print-attendance', $attendance->id) }}"
                                                class="btn btn-secondary btn-sm" target="_blank">
                                                <em class="icon ni ni-printer"></em> Cetak
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <!-- Kalender Kehadiran -->
            <div class="card mt-4 card-bordered card-preview">
                <div class="card-inner py-3 border-bottom border-light">
                    <h4 class="card-title text-center">Calendar</h4>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Modal Print -->
        <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printModalLabel">Cetak Kehadiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="printForm" action="{{ route('pegawai.printcustom-attendance') }}" method="GET">
                        <div class="modal-body">
                            <!-- Input Bulan -->
                            <div class="form-group">
                                <label class="form-label">Bulan</label>
                                <div class="form-control-wrap">
                                    <input type="month" class="form-control month-picker" name="month" required>
                                </div>
                            </div>

                            <!-- Input Tahun -->
                            <div class="form-group mt-3">
                                <label class="form-label">Tahun</label>
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control year-picker" name="year"
                                        placeholder="Masukkan Tahun" min="1900" max="2099" step="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php

        $user = Auth::user();
        $jadwalMasuk = \Carbon\Carbon::createFromTime(8, 0); // Default jadwal masuk
        $jadwalPulang = \Carbon\Carbon::createFromTime(16, 0); // Default jadwal pulang
        $dayName = now()->locale('id')->dayName; // Contoh: "Senin", "Selasa"

        // Ambil jadwal sesuai schedule_id dan nama hari
        if ($user && $user->schedule) {
            $schedule = \App\Models\Schedule::find($user->schedule);

            if ($schedule) {
                $scheduleDay = \App\Models\ScheduleDayM::where('schedule_id', $schedule->id)
                    ->where('days', $dayName)
                    ->first();

                if ($scheduleDay && $scheduleDay->clock_in && $scheduleDay->clock_out) {
                    $jadwalMasuk = \Carbon\Carbon::createFromFormat('H:i:s', $scheduleDay->clock_in);
                    $jadwalPulang = \Carbon\Carbon::createFromFormat('H:i:s', $scheduleDay->clock_out);
                }
            }
        }

        // Map ke dalam events untuk kalender
        $events = $attendances
            ->map(function ($att) use ($jadwalMasuk, $jadwalPulang) {
                $time = \Carbon\Carbon::parse($att->time);
                $formattedTime = $time->format('H:i');

                // Pastikan tanggalnya sama untuk dibandingkan
                $date = \Carbon\Carbon::parse($att->date)->format('Y-m-d');
                $jadwalMasukFull = \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $jadwalMasuk->format('H:i:s'),
                );
                $jadwalPulangFull = \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $jadwalPulang->format('H:i:s'),
                );

                $indicator = '';

                if ($att->status == 0) {
                    // Absen Masuk
                    if ($time->lte($jadwalMasukFull)) {
                        $indicator = ' (Tepat Waktu)';
                    } else {
                        $indicator = ' (Terlambat)';
                    }
                } elseif ($att->status == 1) {
                    // Absen Pulang
                    if ($time->lt($jadwalPulangFull)) {
                        $indicator = ' (Lebih Awal)';
                    }
                }

                return [
                    'title' => $formattedTime . $indicator,
                    'start' => $att->date,
                    'backgroundColor' => $att->status == 0 ? '#28a745' : '#fd7e14',
                    'borderColor' => $att->status == 0 ? '#28a745' : '#fd7e14',
                    'textColor' => str_contains($indicator, 'Terlambat') ? '#000000' : '#ffffff', // merah jika terlambat
                    'id' => $att->id,
                ];
            })
            ->values()
            ->toArray();

    @endphp
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events),
                eventClick: function(info) {
                    printAttendance(info.event.id);
                }
            });

            calendar.render();
        });

        function printAttendance(id) {
            var attendanceData = @json($attendances->keyBy('id'));
            var data = attendanceData[id];

            if (!data) {
                alert("Data tidak ditemukan.");
                return;
            }

            let statusText = data.status == 0 ? 'Masuk' : (data.status == 1 ? 'Pulang' : 'Tidak Diketahui');

            let printContent = `
            <h2>Detail Kehadiran</h2>
            <p><strong>Tanggal:</strong> ${data.date}</p>
            <p><strong>Waktu:</strong> ${data.time}</p>
            <p><strong>Status:</strong> ${statusText}</p>
            <p><strong>Koordinat:</strong> ${data.coordinate}</p>
            <div id="mapPrint" style="height: 300px; margin-top: 10px;"></div>
        `;

            document.getElementById('printContent').innerHTML = printContent;
            document.getElementById('printModal').style.display = 'block';

            // Delay to ensure modal renders before map initializes
            setTimeout(() => {
                var map = L.map('mapPrint').setView([data.latitude, data.longitude], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                }).addTo(map);
                L.marker([data.latitude, data.longitude]).addTo(map);
            }, 300);

            setTimeout(() => {
                window.print();
                document.getElementById('printModal').style.display = 'none';
            }, 1000);
        }
    </script>
@endsection
