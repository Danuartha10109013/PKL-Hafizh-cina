@extends('layout.pegawai.main')

@section('title')
    Dashboard {{ auth()->user()->name }}
@endsection

@section('content-pegawai')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-content p-0">
                    <div class="nk-content-inner">
                        <div class="row g-gs">

                            <!-- Card: Absensi -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-3">
                                            <div class="card-title">
                                                <h6 class="title">Selamat Datang</h6>
                                            </div>
                                        </div>
                                        <div class="user-activity-group g-4">
                                            <div class="user-activity">
                                                <div class="info">
                                                    <span class="amount"> {{ auth()->user()->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .col -->

                            <!-- Card: Total Pegawai -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-3">
                                            <div class="card-title">
                                                <h6 class="title">Saldo Cuti Anda</h6>
                                            </div>
                                        </div>
                                        <div class="user-activity-group g-4">
                                            <div class="user-activity">
                                                <em class="icon ni ni-growth"></em>
                                                <div class="info">
                                                    <span class="amount">{{ Auth::user()->available }} Hari</span>
                                                    <span class="title">Gunakan Sebaik - baiknya</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .col -->

                            <!-- Card: Jabatan -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-3">
                                            <div class="card-title">
                                                <h6 class="title">Waktu Sekarang</h6>
                                            </div>
                                        </div>
                                        <div class="user-activity-group g-4">
                                            <div class="user-activity">
                                                <em class="icon ni ni-clock-fill"></em>
                                                <div class="info">
                                                    <span id="current-time" class="amount"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .col -->
                            <div class="col-md-6">
                                <div class="card card-bordered card-full bg-secondary">
                                    <div class="card-inner">
                                        <div class="row">
                                            <!-- Left section: Welcome message and attendance button -->
                                            <div class="col-6 d-flex flex-column justify-content-center">
                                                <div class="card-title-group align-start mb-3">
                                                    <div class="card-title">
                                                        <h6 class="title text-white">Selamat Datang di Sistem Kehadiran
                                                            Pegawai</h6>
                                                    </div>
                                                </div>
                                                <div class="user-activity-group g-4">
                                                    <div class="user-activity">
                                                        <div class="info">
                                                            <a href="{{ route('pegawai.attendance') }}"
                                                                class="btn btn-light">Absensi</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->

                                            <!-- Right section: Display image -->
                                            <div class="col-6 d-flex align-items-center justify-content-center">
                                                <img src="{{ asset('demo5/src/images/background/attendance5.png') }}"
                                                    alt="Attendance Image" class="img-fluid" style="max-width: 100%;">
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card -->
                            </div><!-- .col -->
                            <div class=" col-md-6">
                                <div class="card card-bordered card-full bg-secondary">
                                    <div class="card-inner">
                                        <div class="row">
                                            <!-- Right section: Display image -->
                                            <div class="col-6 d-flex align-items-center justify-content-center">
                                                <img src="{{ asset('demo5/src/images/background/attendance5.png') }}"
                                                    alt="Attendance Image" class="img-fluid"
                                                    style="max-width: 100%; transform: scaleX(-1);">
                                            </div><!-- .col -->
                                            <!-- Left section: Welcome message and attendance button -->
                                            <div class="col-6 d-flex flex-column justify-content-center align-items-end">
                                                <!-- Menambahkan align-items-end -->
                                                <div class="card-title-group align-start mb-3 text-end"
                                                    style="direction: rtl; text-align: right;">
                                                    <!-- Menambahkan style untuk arah teks -->
                                                    <div class="card-title">
                                                        <h6 class="title text-white">Selamat Datang di Sistem Cuti
                                                            Pegawai</h6>
                                                    </div>
                                                </div>
                                                <div class="user-activity-group g-4">
                                                    <div class="user-activity">
                                                        <div class="info">
                                                            <a href="{{ route('pegawai.leaves') }}"
                                                                class="btn btn-light">Cuti</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->



                                        </div><!-- .row -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card -->
                            </div><!-- .col -->


                            <div class="col-md-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-3">
                                            <div class="card-title">
                                                <h6 class="title">Pengumuman</h6>
                                            </div>
                                        </div>
                                        <div class="user-activity-group g-4">
                                            <div class="user-activity">
                                                <div class="info">
                                                    <span class="tittle">Selamat anda menjadi bagian dari perusahaan Kami,
                                                        Mohon atas kerja sama nya</span>
                                                    <span class="title">Dimohon ketika memasuki Kantor absen terlebih
                                                        dahulu dan Gunakan Cuti dengan sebaik baiknya, disaat genting
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .card -->
                            </div><!-- .col -->
                        </div><!-- .row -->

                        <!-- Section: Calendar -->
                    </div><!-- .nk-content-inner -->
                </div><!-- .nk-content -->
            </div><!-- .container-xl -->
        </div><!-- .nk-content-fluid -->
    </div><!-- .nk-content -->

    <script>
        // Function to update the time
        function updateTime() {
            const currentTimeElement = document.getElementById('current-time');
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            currentTimeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Call the function initially
        updateTime();

        // Update the time every second
        setInterval(updateTime, 1000);
    </script>
@endsection
