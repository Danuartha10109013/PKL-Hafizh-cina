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
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview shadow-sm">
                            <div class="card-inner">
                                <div class="row">
                                    @foreach ($data as $item)
                                        <!-- Kiri: Informasi Personal -->
                                        <div class="col-md-4 text-center mb-4">
                                            <!-- Display the current avatar -->

                                            <img src="{{ asset($item->avatar) }}" alt="{{ $item->avatar }}'s avatar"
                                                class="img-fluid rounded-circle shadow-sm" style="max-width: 150px;">

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
                                                <button type="submit" class="btn btn-primary mt-2">Update Avatar</button>
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
                                                                        name="username" value="{{ $item->username }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Hak Akses</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $role }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Perangkat Seluler</label>
                                                                    <input type="text" class="form-control"
                                                                        name="telephone" value="{{ $item->telephone }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Status Akun</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $active }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary">Simpan
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
                                                                    <label class="form-label">Tempat Lahir</label>
                                                                    <input type="text" class="form-control"
                                                                        name="place_of_birth"
                                                                        value="{{ $item->place_of_birth }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Tanggal Lahir</label>
                                                                    <input type="date" class="form-control"
                                                                        name="date_of_birth"
                                                                        value="{{ $item->date_of_birth }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jenis Kelamin</label>
                                                                    <input type="text" class="form-control"
                                                                        name="gender" value="{{ $item->gender }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Agama</label>
                                                                    <input type="text" class="form-control"
                                                                        name="religion" value="{{ $item->religion }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary">Simpan
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
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Alamat</label>
                                                                    <input type="text" class="form-control"
                                                                        name="address" value="{{ $item->address }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Jabatan</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $item->position }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary">Simpan
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
            let hash = window.location.hash;
            if (hash) {
                let activeTab = document.querySelector('a[href="' + hash + '"]');
                if (activeTab) {
                    activeTab.click();
                }
            }

            let tabLinks = document.querySelectorAll('.nav-tabs a');
            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    let target = this.getAttribute('href');
                    window.history.pushState(null, null, target);
                    let tab = new bootstrap.Tab(this);
                    tab.show();
                });
            });
        });
    </script>
@endsection