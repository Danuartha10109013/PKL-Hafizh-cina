<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/faviconlogo.png') }}">
    <!-- Page Title -->
    <title>Login Kehadiran</title>
    <!-- StyleSheets -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">

    <style>
        body {
            background-image: url('{{ asset('demo5/src/images/background/attendance.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="nk-body npc-general pg-auth">
    <div class="nk-app-root">
        <div class="nk-split nk-split-page nk-split-md">
            <!-- Login Form -->
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="{{ route('auth.login') }}" class="logo-link">
                            <img class="logo-light logo-img logo-img-lg"
                                src="{{ asset('demo5/src/images/logonew1.png') }}"
                                srcset="{{ asset('demo5/src/images/logonew1.png') }}" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg"
                                src="{{ asset('demo5/src/images/logonew1.png') }}"
                                srcset="{{ asset('demo5/src/images/logonew1.png') }}" alt="logo-dark">
                        </a>
                    </div>
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Login</h5>
                            <div class="nk-block-des">
                                <p>Akses sistem kehadiran menggunakan username atau email dan password Anda.</p>
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('login-proses') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="username">Username atau Email</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="username" class="form-control form-control-lg"
                                    id="username" placeholder="Masukkan username atau email anda">
                            </div>
                        </div>
                        @error('username')
                            <small>{{ $message }}</small>
                        @enderror
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">Password</label>
                                <a class="link link-primary link-sm" href="{{ route('resetpassword') }}">Forgot
                                    Password?</a>
                            </div>
                            <div class="form-control-wrap">
                                <a href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye" style="display:none;"></em>
                                </a>
                                <input type="password" name="password" class="form-control form-control-lg"
                                    id="password" placeholder="Masukkan Password anda">
                            </div>
                        </div>
                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                        <div class="form-group">
                            <button class="btn btn-lg btn-secondary btn-block">Login</button>
                        </div>
                    </form><!-- form -->
                </div><!-- .nk-block -->
                <div class="nk-block nk-auth-footer">
                    <div class="mt-3">
                        <p>&copy; 2024 Sistem Kehadiran Pegawai. All Rights Reserved.</p>
                    </div>
                </div><!-- .nk-block -->
            </div><!-- .nk-split-content -->
            <!-- Attendance Section -->
            <div class="nk-split-content nk-split-stretch bg-abstract">
                {{-- <div class="nk-block nk-auth-body">
                    <div class="brand-logo pb-4 text-center">
                        <a href="#" class="logo-link">
                            <img src="{{ asset('demo5/src/images/background/attendance.jpg') }}" alt="Attendance">
                        </a>
                    </div>
                    <div class="nk-block">
                        <!-- Add your attendance content here -->
                    </div><!-- .nk-block -->
                </div><!-- .nk-block --> --}}
            </div><!-- .nk-split-content -->
        </div><!-- .nk-split -->
    </div><!-- app-root @e -->
    <!-- JavaScript -->
    <script src="./assets/js/bundle.js?ver=3.0.3"></script>
    <script src="./assets/js/scripts.js?ver=3.0.3"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.passcode-switch');
            const passwordInput = document.getElementById('password');
            const iconShow = togglePassword.querySelector('.icon-show');
            const iconHide = togglePassword.querySelector('.icon-hide');

            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    iconShow.style.display = 'none';
                    iconHide.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    iconShow.style.display = 'block';
                    iconHide.style.display = 'none';
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const usernameInput = document.querySelector('input[name="username"]');
            const passwordInput = document.querySelector('input[name="password"]');
            const loginButton = document.querySelector('button[type="submit"]');

            loginForm.addEventListener('submit', function(e) {
                // Validasi frontend: periksa apakah input kosong
                if (!usernameInput.value.trim() || !passwordInput.value.trim()) {
                    e.preventDefault(); // Mencegah form submit
                    Swal.fire({
                        title: 'Data tidak boleh kosong',
                        text: 'Harap isi username atau password anda.',
                        icon: 'warning',
                        confirmButtonColor: '#364a63'
                    });
                    return; // Hentikan eksekusi
                }
            });

            // Validasi backend: gunakan pesan dari session
            @if ($message = Session::get('succes'))
                Swal.fire({
                    title: '{{ $message }}',
                    icon: 'success',
                    confirmButtonColor: '#364a63'
                }).then(() => {
                    loginButton.style.backgroundColor = '#364a63'; // Secondary
                    loginButton.style.borderColor = '#364a63';
                });
            @endif

            @if ($message = Session::get('failed'))
                Swal.fire({
                    title: '{{ $message }}',
                    icon: 'error',
                    confirmButtonColor: '#364a63'
                }).then(() => {
                    loginButton.style.backgroundColor = '#364a63'; // Secondary
                    loginButton.style.borderColor = '#364a63';
                });
            @endif
        });
    </script>

</body>

</html>
