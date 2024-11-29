@extends('layout.main')
@section('title', 'Tambah Pegawai')

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
                            <h2 class="nk-block-title fw-normal">Tambah Pegawai</h2>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('admin.tambahpegawaistore') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Form Tambah Pegawai</span>
                                        <div class="row gy-4">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="username">Username</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="username"
                                                            name="username" value="{{ $nextUsername }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">Nama</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ old('name') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Email</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" value="{{ old('email') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="role">Role</label>
                                                    <div class="form-control-wrap">
                                                        <select
                                                            class="form-select js-select2 select2-hidden-accesible valid"
                                                            id="role" name="role" placeholder="Pilih Role">
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ old('role') == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
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
                                                            name="password">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-secondary">Tambah Pegawai</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .code-block -->
                </div><!-- .nk-block -->
            </div><!-- .nk-content-body -->
        </div><!-- .container-xl -->
    </div><!-- .nk-content -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            form.addEventListener('submit', function(e) {
                let errorMessage = '';

                // Validasi nama
                if (!nameInput.value.trim()) {
                    errorMessage += 'Nama tidak boleh kosong.<br>';
                }

                // Validasi email
                if (!emailInput.value.trim()) {
                    errorMessage += 'Email tidak boleh kosong.<br>';
                } else if (!emailInput.value.includes('@')) {
                    errorMessage += 'Email harus mengandung karakter "@" yang valid.<br>';
                }

                // Validasi password
                const password = passwordInput.value.trim();
                const passwordRegex =
                    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!password) {
                    errorMessage += 'Password tidak boleh kosong.<br>';
                } else if (!passwordRegex.test(password)) {
                    errorMessage +=
                        'Password harus minimal 8 karakter dan mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol.<br>';
                }

                // Tampilkan error menggunakan SweetAlert
                if (errorMessage) {
                    e.preventDefault(); // Cegah pengiriman formulir
                    Swal.fire({
                        title: 'Data Tidak Lengkap atau Tidak Valid!',
                        html: errorMessage,
                        icon: 'warning',
                        confirmButtonColor: '#364a63'
                    });
                }
            });

            // Validasi input nama hanya huruf dan spasi
            nameInput.addEventListener('input', function(event) {
                const input = event.target;
                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
            });
        });
    </script>



@endsection
