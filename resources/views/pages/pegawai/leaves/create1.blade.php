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
                            <h3 class="nk-block-title page-title">Pengajuan Cuti </h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                            </div><!-- .toggle-wrap -->
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
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="username">Alasan Cuti</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="reason_verification"
                                                        name="reason_verification" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Perihal Cuti</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="about" name="about"
                                                        value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Mulai Cuti</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar-alt"></em>
                                                    </div>
                                                    <input type="text" class="form-control date-picker" id="date"
                                                        name="date" data-date-format="dd M yyyy" required>
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
                                                    <input type="text" class="form-control date-picker" id="end_date"
                                                        name="end_date" data-date-format="dd M yyyy" required>
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
    {{-- 
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                    showConfirmButton: true,
                });
            });
        </script>
    @endif --}}
@endsection
