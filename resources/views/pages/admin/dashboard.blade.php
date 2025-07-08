@extends('layout.main')
@section('title')
    Dashboard Admin
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                {{-- UNTUK MENAMPILKAN BAHWA DISINI SUDAH LOGIN TAMPILAN ADMIN --}}
                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-md-6">
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <div class="card-head text-center">
                                        <h6 class="title">Total Pegawai</h6>
                                    </div>
                                    <div class="nk-ck-sm d-flex align-items-start gap-4">
                                        <div style="width: 70%;">
                                            <canvas class="pie-chart" id="pieChartData"></canvas>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center gap-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-primary"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Laki-Laki: <strong>{{ $maleCount }}</strong></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-danger"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Perempuan: <strong>{{ $femaleCount }}</strong></span>
                                            </div>
                                            <hr class="my-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-dark"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Total Pegawai: <strong>{{ $maleCount + $femaleCount }}</strong></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div><!-- .card-preview -->
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <div class="card-head text-center">
                                        <h6 class="title">Statistik Kehadiran</h6>
                                    </div>
                                    <div class="nk-ck-sm d-flex align-items-start gap-4">
                                        <div style="width: 70%;">
                                            <canvas class="pie-chart" id="pieChartData2"></canvas>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center gap-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-purple"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Cuti: <strong>{{ $attendanceStats['cuti'] }}</strong></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-success"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Masuk: <strong>{{ $attendanceStats['masuk'] }}</strong></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-warning"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Pulang: <strong>{{ $attendanceStats['pulang'] }}</strong></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dot bg-info"
                                                    style="width:10px; height:10px; border-radius:50%; display:inline-block;"></span>
                                                <span>Absen: <strong>{{ $attendanceStats['absen'] }}</strong></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div><!-- .card-preview -->
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-3">
                                        <div class="card-title">
                                            @php
                                                \Carbon\Carbon::setLocale('id');
                                            @endphp
                                            <h6 class="title">Daftar Pegawai Terlambat :
                                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y', 'id') }}</h6>

                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pegawai</th>
                                                    <th>Waktu Masuk</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($terlambat as $index => $pegawai) --}}
                                                @foreach ($telatHariIni as $tel)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            @php
                                                                $name = \App\Models\User::where(
                                                                    'id',
                                                                    $tel->enhancer,
                                                                )->value('name');
                                                            @endphp
                                                            {{ $name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($tel->time)->format('H:i') }}</td>
                                                        <td><span class="badge bg-danger">Absen Terlambat</span></td>
                                                    </tr>
                                                @endforeach

                                                {{-- @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->

                        <div class="col-md-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-3">
                                        <div class="card-title">
                                            @php
                                                \Carbon\Carbon::setLocale('id');
                                            @endphp
                                            <h6 class="title">Daftar Pegawai Tepat Waktu :
                                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y', 'id') }}</h6>

                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pegawai</th>
                                                    <th>Waktu Masuk</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($hadir as $index => $pegawai) --}}
                                                @foreach ($tepatHariIni as $tel)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            @php
                                                                $name = \App\Models\User::where(
                                                                    'id',
                                                                    $tel->enhancer,
                                                                )->value('name');
                                                            @endphp
                                                            {{ $name }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($tel->time)->format('H:i') }}</td>
                                                        <td><span class="badge bg-danger">Absen Terlambat</span></td>
                                                    </tr>
                                                @endforeach

                                                {{-- @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->

                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
            <div class="nk-content p-0">
                <div class="nk-content-inner">
                    <div class="nk-content-body p-0">
                        <div class="nk-block">
                            <div class="card bg-transparent">
                                <div class="card-inner py-3 border-bottom border-light rounded-0">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title page-title">Calendar</h3>
                                            </div><!-- .nk-block-head-content -->
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .nk-block-head -->
                                </div>
                            </div>
                            <div class="card mt-0">
                                <div class="card-inner">
                                    <div id="calendar" class="nk-calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pastikan data dari controller sudah dikirim
            const maleCount = @json($maleCount);
            const femaleCount = @json($femaleCount);
            const attendanceStats = @json($attendanceStats); // Periksa apakah ini mengandung data yang benar

            console.log(attendanceStats); // Debug untuk melihat apakah data diterima di frontend

            // Chart untuk Gender
            var ctx = document.getElementById('pieChartData').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Laki-Laki', 'Perempuan'],
                    datasets: [{
                        data: [maleCount, femaleCount],
                        backgroundColor: ['#b695ff', '#f4aaa4'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#eff6ff',
                            titleFont: {
                                size: 13,
                                color: '#6783b8'
                            },
                            titleMarginBottom: 6,
                            bodyFont: {
                                color: '#9eaecf',
                                size: 12
                            },
                            bodySpacing: 4,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                }
            });

            // Chart untuk Statistik Kehadiran
            var ctx2 = document.getElementById('pieChartData2').getContext('2d');
            var pieChart2 = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Cuti', 'Masuk', 'Pulang', 'Absen'],
                    datasets: [{
                        data: [
                            attendanceStats.cuti,
                            attendanceStats.masuk,
                            attendanceStats.pulang,
                            attendanceStats.absen
                        ],
                        backgroundColor: ['#b695ff', '#39e369', '#cce339', '#5fc2b5'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#eff6ff',
                            titleFont: {
                                size: 13,
                                color: '#6783b8'
                            },
                            titleMarginBottom: 6,
                            bodyFont: {
                                color: '#9eaecf',
                                size: 12
                            },
                            bodySpacing: 4,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                }
            });
        });
    </script>
@endsection
