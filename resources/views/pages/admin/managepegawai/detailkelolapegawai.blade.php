@extends('layout.main')
@section('title')
    Detail Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-sub">
                            <a class="back-to" href="{{ route('admin.kelolapegawai') }}">
                                <em class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span>
                            </a>
                        </div>
                        <h2 class="nk-block-title fw-normal">Detail Pegawai </h2>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview shadow-sm">
                            <div class="card-inner">
                                <div class="row">
                                    <!-- Kiri: Informasi Personal -->
                                    <div class="col-md-4">
                                        <div class="text-center mb-4">
                                            <div class="img-fluid rounded-circle shadow-sm"
                                                style="width: 150px; height: 150px; background-color: #2c3e50; display: flex; align-items: center; justify-content: center; margin: 0 auto; overflow: hidden;">
                                                @if ($item->avatar)
                                                    <img src="{{ asset('storage/' . $item->avatar) }}"
                                                        alt="{{ $item->name }}'s avatar" class="img-fluid text-white"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span>
                                                        No
                                                    </span>
                                                @endif
                                            </div>
                                            <h5 class="mt-3">{{ $item->name }}</h5>
                                            <p class="text-muted">{{ $item->email }}</p>
                                        </div>
                                        <div class="text-center mb-4">
                                            <div class="img-fluid rounded-circle shadow-sm"
                                                style="width: 150px; height: 150px; background-color: #2c3e50; overflow: hidden; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                @if ($item->acuan)
                                                    <img src="{{ asset('storage/' . $item->acuan) }}" alt="Acuan Absensi"
                                                        style="width: 150px; height: 150px; object-fit: cover; object-position: center;">
                                                @else
                                                    <span>No image</span>
                                                @endif
                                            </div>
                                            <h5 class="mt-3">Acuan Absensi</h5>
                                        </div>
                                    </div>



                                    <!-- Kanan: Detail Informasi -->
                                    <div class="col-md-8">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" class="form-control" value="{{ $item->username }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="role">Hak Akses</label>
                                                    <div class="form-control-wrap">
                                                        <select
                                                            class="form-select js-select2 select2-hidden-accesible valid"
                                                            id="role" name="role" disabled>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $role->id == $item->role ? 'selected' : '' }}>
                                                                    {{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Perangkat Seluler</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->telephone }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Status Akun</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->status == 0 ? 'Aktif' : 'Tidak Aktif' }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Tanggal Bergabung</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->created_at->format('d M Y') }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Jam Kerja</label>
                                                    <input type="text" class="form-control" id="schedule"
                                                        name="schedule" value="{{ optional($schedule)->shift_name }}"
                                                        disabled>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->place_of_birth }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Tanggal Lahir</label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-icon form-icon-left">
                                                            <em class="icon ni ni-calendar"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker"
                                                            data-date-format="yyyy-mm-dd"
                                                            value="{{ $item->date_of_birth }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <input type="text" class="form-control" value="{{ $item->gender }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Agama</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->religion }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">KTP</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->id_card }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">NIP</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->nip }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Jabatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->position }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Alamat</label>
                                                    {{-- <input type="text" class="form-control" value="{{ $data->address }}"
                                                        readonly> --}}
                                                    <textarea name="address" class="form-control" id="address" cols="30" rows="10" placeholder="Aalamat"
                                                        disabled>{{ $item->address }}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div><!-- .nk-content-body -->
        </div><!-- .container-xl -->
    </div><!-- .nk-content -->
@endsection
