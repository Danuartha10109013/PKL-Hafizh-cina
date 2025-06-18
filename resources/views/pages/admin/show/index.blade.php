@extends('layout.main')

@section('title')
    Show Button Attendance
@endsection

@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <h4 class="nk-block-title" style="color: #364d65;">Pengaturan Visibilitas Tombol Absensi</h4>
                    <div class="small text-muted">Atur apakah tombol absensi selalu terlihat atau hanya saat jam kerja sesuai
                        jadwal.</div>
                </div>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}',
                            confirmButtonColor: '#364d65'
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#364d65'
                        });
                    </script>
                @endif

                <div class="card card-bordered card-preview shadow-sm">
                    <div class="card-inner">
                        <form action="{{ route('admin.attendance-button.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" style="color: #364d65;">Mode Tampilan Tombol
                                    Absensi</label>

                                <div class="border rounded p-3 mb-2 {{ $data->show == 1 ? 'bg-light' : '' }}">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="show" value="1" class="custom-control-input"
                                            id="showAlways" {{ $data->show == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showAlways">
                                            <span class="fw-semibold" style="color: #364d65;">Selalu Terlihat</span>
                                            <div class="small text-muted">Tombol absensi akan selalu muncul tanpa memeriksa
                                                waktu/jadwal.</div>
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 {{ $data->show == 0 ? 'bg-light' : '' }}">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="show" value="0" class="custom-control-input"
                                            id="showBySchedule" {{ $data->show == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showBySchedule">
                                            <span class="fw-semibold" style="color: #364d65;">Sesuai Jadwal</span>
                                            <div class="small text-muted">Tombol hanya akan terlihat saat waktu absensi yang
                                                dijadwalkan.</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg px-4 py-2"
                                    style="background-color: #364d65; color: #fff;">
                                    <em class="icon ni ni-save me-1"></em> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
