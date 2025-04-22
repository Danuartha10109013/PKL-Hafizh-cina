@extends('layout.pegawai.main')
@section('title')
    Informasi Akun
@endsection
@section('content-pegawai')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-sub">
                            <a class="back-to" href="{{ route('pegawai.pages.pegawai.dashboard') }}">
                                <em class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span>
                            </a>
                        </div>
                        <h2 class="nk-block-title fw-normal"></h2>
                    </div>
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
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview shadow-sm">
                            <div class="card-inner">
                                <div class="row">
                                    @foreach ($data as $item)
                                        <!-- Kiri: Informasi Personal -->
                                        <div class="col-md-4 d-flex flex-column align-items-center text-center mb-4">
                                            <!-- Display the current avatar -->
                                            {{-- <div class="img-fluid rounded-circle shadow-sm"
                                                style="width: 150px; height: 150px; background-color: #2c3e50; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                @if ($item->avatar)
                                                    <img src="{{ asset($item->avatar) }}" alt="{{ $item->name }}'s avatar"
                                                        class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span>No Image</span>
                                                @endif
                                            </div>

                                            <h5 class="mt-3">{{ $item->name }}</h5>
                                            <p class="text-muted">{{ $item->email }}</p>

                                            <!-- Avatar upload form -->
                                            <form action="{{ route('pegawai.updateAvatar', $item->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group">
                                                    <label for="avatar" class="mt-3">Upload New Avatar:</label>
                                                    <input type="file" id="avatar" accept=".jpg,.jpeg,.png"
                                                        name="avatar" class="form-control-file">
                                                </div>
                                                <button type="submit" class="btn btn-secondary mt-2">Update Avatar</button>
                                            </form> --}}
                                            <div class="position-relative img-fluid rounded-circle shadow-sm"
                                                style="width: 150px; height: 150px; background-color: #2c3e50; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: pointer;"
                                                onclick="document.getElementById('avatarInput').click()">
                                                @if ($item->avatar)
                                                    <img src="{{ asset('storage/'.$item->avatar) }}" alt="{{ $item->name }}'s avatar"
                                                        class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @endif
                                                <!-- Text Overlay -->
                                                <div class="position-absolute text-white d-flex align-items-center justify-content-center"
                                                    style="top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); opacity: 0; transition: opacity 0.3s;">
                                                    <span style="font-size: 14px;">Upload Image / Ganti Foto</span>
                                                </div>
                                            </div>

                                            <h5 class="mt-3">{{ $item->name }}</h5>
                                            <p class="text-muted">{{ $item->email }}</p>

                                            <!-- Avatar Upload Form -->
                                            <form id="avatarForm" action="{{ route('pegawai.updateAvatar', $item->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <input type="file" id="avatarInput" accept=".jpg,.jpeg,.png"
                                                    name="avatar" class="d-none"
                                                    onchange="document.getElementById('avatarForm').submit()">
                                            </form>


                                        </div>


                                        <!-- Kanan: Detail Informasi dengan 3 Kolom -->
                                        <div class="col-md-8">
                                            <!-- Tab Navigation -->
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#account-info">
                                                        Akun
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#biodata-info">Biodata</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab"
                                                        href="#identity-info">Identitas</a>
                                                </li>
                                            </ul>

                                            <!-- Tab Content -->
                                            <div class="tab-content mt-3">
                                                <!-- Akun Tab -->
                                                <div class="tab-pane fade show active" id="account-info">
                                                    <form action="{{ route('pegawai.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Username</label>
                                                                    <input type="text" class="form-control"
                                                                        name="username" value="{{ $item->username }}"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">email</label>
                                                                    <input type="text" class="form-control"
                                                                        name="email" value="{{ $item->email }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Hak Akses</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $role }}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Status Akun</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $active }}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-secondary">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Biodata Tab -->
                                                <div class="tab-pane fade" id="biodata-info">
                                                    <form action="{{ route('pegawai.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Nama Lengkap</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" value="{{ $item->name }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Nomor Telepon</label>
                                                                    <input type="number" class="form-control"
                                                                        name="telephone" value="{{ $item->telephone }}"
                                                                        inputmode="numeric"
                                                                        oninput="this.value = this.value.slice(0, 13)"
                                                                        placeholder="Nomor Telepon">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Tempat Lahir</label>
                                                                    <input type="text" class="form-control"
                                                                        name="place_of_birth"
                                                                        value="{{ $item->place_of_birth }}"
                                                                        placeholder="Tempat Lahir">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Tanggal Lahir</label>
                                                                    <input type="text" class="form-control date-picker"
                                                                        name="date_of_birth" data-date-format="dd M yyyy"
                                                                        value="{{ \Carbon\Carbon::parse($item->date_of_birth)->format('d M Y') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jenis Kelamin</label>
                                                                    <input type="text" class="form-control"
                                                                        name="gender" value="{{ $item->gender }}"
                                                                        placeholder="Jenis Kelamin">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Agama</label>
                                                                    <input type="text" class="form-control"
                                                                        name="religion" value="{{ $item->religion }}"
                                                                        placeholder="Agama">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Alamat</label>
                                                                    <textarea type="text" class="form-control" name="address" placeholder="Alamat">{{ $item->address }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-secondary">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Identitas Tab -->
                                                <div class="tab-pane fade" id="identity-info">
                                                    <form action="{{ route('pegawai.update', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jabatan</label>
                                                                    <input type="text" class="form-control"
                                                                        name="" value="{{ $item->position }}"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="id_card">KTP</label>
                                                                    <input type="text" class="form-control"
                                                                        name="id_card" value="{{ $item->id_card }}"
                                                                        maxlength="16" inputmode="numeric" pattern="\d*"
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                                                                        placeholder="KTP">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Tanggal Bergabung</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jam Kerja</label>
                                                                    <input type="text" class="form-control"
                                                                        name="schedule" value="{{ $schedule }}"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="nip">NIP</label>
                                                                    <input type="text" class="form-control"
                                                                        name="nip" value="{{ $item->nip }}"
                                                                        maxlength="16" inputmode="numeric" pattern="\d*"
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                                                                        placeholder="NIP">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-secondary">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div><!-- .card-preview -->
                </div><!-- .nk-block -->
            </div><!-- .components-preview -->
        </div><!-- .nk-content-body -->
    </div><!-- .container-xl -->
    </div><!-- .nk-content -->

    <!-- Script untuk Tab Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tabLinks = document.querySelectorAll('.nav-tabs a');

            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah perilaku default
                    let targetTab = this.getAttribute('href'); // Mendapatkan target tab
                    let tab = new bootstrap.Tab(this); // Inisialisasi tab Bootstrap

                    // Menampilkan tab yang dipilih
                    tab.show();

                    // Mengupdate hash di URL tanpa reload halaman
                    window.location.hash = targetTab;
                });
            });

            // Saat halaman dimuat, cek hash di URL dan aktifkan tab yang sesuai
            let activeTab = window.location.hash;
            if (activeTab) {
                let activeTabLink = document.querySelector('a[href="' + activeTab + '"]');
                if (activeTabLink) {
                    let tab = new bootstrap.Tab(activeTabLink);
                    tab.show();
                }
            }
        });
    </script>
@endsection
