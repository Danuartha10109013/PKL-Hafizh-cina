@extends('layout.main')

@section('title')
    Edit Pegawai
@endsection

@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <div class="nk-block-head-sub">
                                <a class="back-to" href="{{ route('admin.kelolapegawai') }}">
                                    <em class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span>
                                </a>
                            </div>
                            <h2 class="nk-block-title fw-normal">Edit Pegawai</h2>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                @if ($errors->any())
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Pastikan semua kolom terisi dengan benar!',
                                        });
                                    </script>
                                @endif
                                <form action="{{ route('admin.editpegawaiupdate', $item->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Form Edit Pegawai</span>
                                        <div class="row gy-4">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="username">Username</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="username"
                                                            name="username" value="{{ $item->username }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">Nama</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ $item->name }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Email</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" value="{{ $item->email }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="created_at">Tanggal Bergabung</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="created_at"
                                                            name="created_at"
                                                            value="{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="role">Hak Akses</label>
                                                    <div class="form-control-wrap">
                                                        <select
                                                            class="form-select js-select2 select2-hidden-accesible valid"
                                                            id="role" name="role">

                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $role->id == $item->role ? 'selected' : '' }}>
                                                                    {{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="password">Password</label>
                                                    <div class="form-control-wrap">
                                                        <input type="password" class="form-control" id="password"
                                                            name="password"
                                                            placeholder="Leave blank to keep current password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status</label>
                                                    <div class="form-control-wrap">
                                                        <select
                                                            class="form-select js-select2 select2-hidden-accesible valid"
                                                            id="status" name="status">
                                                            <option value="0"
                                                                {{ $item->status == 0 ? 'selected' : '' }}>Aktif</option>
                                                            <option value="1"
                                                                {{ $item->status == 1 ? 'selected' : '' }}>Tidak Aktif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="avatar">Avatar</label>
                                                    <div class="form-control-wrap">
                                                        <input type="file" class="form-file-input" id="avatar"
                                                            name="avatar" accept=".jpg,.jpeg,.png" placeholder="">
                                                        <label class="form-file-label" for="avatar">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="avatar">Acuan Absen</label>
                                                    <div class="form-control-wrap">
                                                        <input type="file" class="form-file-input" id="avatar"
                                                            name="acuan" accept=".jpg,.jpeg,.png" placeholder="">
                                                        <label class="form-file-label" for="avatar">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="schedule">Jadwal</label>
                                                    <div class="form-control-wrap">
                                                        <select
                                                            class="form-select js-select2 select2-hidden-accesible valid"
                                                            id="schedule" name="schedule">
                                                            <option value=""
                                                                {{ $item->schedule == null ? 'selected' : '' }}>Belum ada
                                                                jadwal</option>
                                                            @foreach ($schedules as $schedule)
                                                                <option value="{{ $schedule->id }}"
                                                                    {{ $schedule->id == $item->schedule ? 'selected' : '' }}>
                                                                    {{ $schedule->shift_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Tempat Lahir</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="place_of_birth"
                                                            name="place_of_birth" value="{{ $item->place_of_birth }}"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">Tanggal Lahir</label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-icon form-icon-left">
                                                            <em class="icon ni ni-calendar"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker"
                                                            id="date_of_birth" name="date_of_birth"
                                                            data-date-format="yyyy-mm-dd"
                                                            value="{{ $item->date_of_birth }}" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <input type="text" class="form-control" name="gender"
                                                        value="{{ $item->gender }}" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Perangkat Seluler</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="telephone"
                                                            name="telephone" value="{{ $item->telephone }}"
                                                            maxlength="13" oninput="this.value = this.value.slice(0, 13)"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">Agama</label>
                                                    <input type="text" class="form-control" name="religion"
                                                        value="{{ $item->religion }}" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">KTP</label>
                                                    <input type="number" class="form-control" name="id_card"
                                                        value="{{ $item->id_card }}" maxlength="16"
                                                        oninput="this.value = this.value.slice(0, 16)">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="address">Alamat</label>
                                                    <div class="form-control-wrap">
                                                        <textarea type="text" class="form-control" id="address" name="address">{{ $item->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-secondary">Update Pegawai</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
