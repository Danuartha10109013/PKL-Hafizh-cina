@extends('layout.pegawai.main')

@section('title')
    Pengajuan Cuti
@endsection

@section('content-pegawai')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <div class="nk-block-head-sub">
                                <a class="back-to" href="{{ route('pegawai.leaves') }}">
                                    <em class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span>
                                </a>
                            </div>
                            <h3 class="nk-block-title page-title">Pengajuan Cuti</h3>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <form action="{{ route('pegawai.store-cuti') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="preview-block">
                                    <span class="preview-title-lg overline-title">Form Pengajuan Cuti</span>
                                    <div class="row gy-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="enhancer" value="{{ Auth::user()->id }}" hidden>
                                                <label class="form-label" for="kategori">Kategori Cuti</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select js-select2" id="kategori" name="category"
                                                        required>
                                                        <option value="annual">Cuti Tahunan</option>
                                                        <option value="other">Cuti Lain-lain</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form untuk Cuti Lain-lain -->
                                        <div id="form-lain" style="display: none;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="subkategori">Subkategori</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select js-select2" id="subkategori"
                                                            name="subcategory">
                                                            <option value="sick">Sakit</option>
                                                            <option value="married">Menikah</option>
                                                            <option value="important_reason">Beralasan Penting</option>
                                                            <option value="pilgrimage">Ibadah Haji</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="surat_cuti">Surat Cuti</label>
                                                    <div class="form-control-wrap">
                                                        <input type="file" class="form-control" id="leave_letter"
                                                            name="leave_letter">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Field Alasan Cuti (Ditampilkan pada kedua kategori) -->
                                        <div id="form-alasan" style="display: none;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="alasan">Alasan Cuti</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="reason"
                                                            name="reason" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form untuk Mulai dan Berakhir -->
                                        <div id="form-tanggal" style="display: none;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Mulai Cuti</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-icon form-icon-right">
                                                                <em class="icon ni ni-calendar-alt"></em>
                                                            </div>
                                                            <input type="text" data-date-format="dd M yyyy"
                                                                class="form-control date-picker" id="date"
                                                                name="date" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Berakhir Cuti</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-icon form-icon-right">
                                                                <em class="icon ni ni-calendar-alt"></em>
                                                            </div>
                                                            <input type="text" data-date-format="dd M yyyy"
                                                                class="form-control date-picker" id="end_date"
                                                                name="end_date" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr class="preview-hr">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary">Ajukan Cuti</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
            </div>
        </div>
    </div>

    <!-- Tambahkan CDN Datepicker jika belum ada -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kategori').select2();
            $('#subkategori').select2();

            // Elemen form
            var formAlasan = document.getElementById('form-alasan');
            var formLain = document.getElementById('form-lain');
            var formTanggal = document.getElementById('form-tanggal');

            $('#kategori').on('change', function() {
                var kategori = $(this).val();

                if (kategori === 'annual') {
                    formAlasan.style.display = 'block';
                    formLain.style.display = 'none';
                    formTanggal.style.display = 'block';
                } else if (kategori === 'other') {
                    formAlasan.style.display = 'block';
                    formLain.style.display = 'block';
                    formTanggal.style.display = 'block';
                } else {
                    formAlasan.style.display = 'none';
                    formLain.style.display = 'none';
                    formTanggal.style.display = 'none';
                }
            });

            // Konfigurasi datepicker agar tidak bisa memilih tanggal di masa lalu
            var today = new Date();
            $('.date-picker').datepicker({
                format: 'dd M yyyy',
                startDate: today,
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
