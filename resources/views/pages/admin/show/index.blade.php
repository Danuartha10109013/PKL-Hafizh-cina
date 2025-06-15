@extends('layout.main')

@section('title')
    Show Button Attendance
@endsection

@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <h4 class="nk-block-title">Pengaturan Visibilitas Tombol Absensi</h4>
                    <div class="small text-muted">Atur apakah tombol absensi selalu terlihat atau hanya saat jam kerja sesuai jadwal.</div>
                </div>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}'
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: '{{ session('error') }}'
                        });
                    </script>
                @endif

                <div class="card card-bordered card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{ route('admin.attendance-button.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">Mode Tampilan Tombol Absensi</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="show" value="1" class="custom-control-input" id="showAlways" {{ $data->show == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="showAlways">
                                        Selalu Terlihat
                                        <div class="small text-muted">Tombol absensi akan selalu muncul tanpa memeriksa waktu/jadwal.</div>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio mt-2">
                                    <input type="radio" name="show" value="0" class="custom-control-input" id="showBySchedule" {{ $data->show == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="showBySchedule">
                                        Sesuai Jadwal
                                        <div class="small text-muted">Tombol hanya akan terlihat saat waktu absensi yang dijadwalkan.</div>
                                    </label>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <em class="icon ni ni-save"></em> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
