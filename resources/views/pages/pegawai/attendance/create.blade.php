@extends('layout.pegawai.main')
@section('title')
    Absensi
@endsection
@section('content-pegawai')
    <div class="container">
        <h1>Absen Masuk/Pulang</h1>

        <!-- Alert untuk diluar area yang diizinkan -->
        <div id="outOfAreaAlert" class="alert alert-pro alert-danger" style="display: none;">
            <div class="alert-text">
                <h6>Diluar Area yang Diizinkan</h6>
                <p>Anda berada di luar lokasi yang diizinkan untuk absen</p>
            </div>
        </div>

        <!-- Alert untuk dalam area yang diizinkan -->
        <div id="inAreaAlert" class="alert alert-pro alert-success" style="display: none;">
            <div class="alert-text">
                <h6>Lokasi Ditemukan</h6>
                <p>Anda berada di lokasi yang diizinkan untuk absen.</p>
            </div>
        </div>

        <!-- Alert untuk gagal mendapatkan lokasi -->
        <div id="locationErrorAlert" class="alert alert-pro alert-danger" style="display: none;">
            <div class="alert-text">
                <h6>Gagal mendapatkan lokasi</h6>
                <p>Silakan periksa izin lokasi dan coba lagi.</p>
            </div>
        </div>

        <form action="{{ route('pegawai.store-attendance') }}" method="POST" id="attendanceForm">
            @csrf

            <!-- Tombol untuk memilih status kehadiran -->
            <div class="form-group">
                <label for="status">Status Kehadiran</label>
                <select name="status" id="status" class="form-select js-select2 select2-hidden-accesible valid">
                    <option value="0">Masuk</option>
                    <option value="1">Pulang</option>
                </select>
            </div>

            <div class="form-group">
                <div style="position: relative; width: 100%; max-height: 700px; overflow: hidden;">
                    <video id="video" style="width: 100%; height: auto;" autoplay></video>
                    <canvas id="canvas"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;"></canvas>
                </div>
                <button id="captureFace" class="btn btn-primary mt-2" type="button" disabled>Ambil Foto</button>
                <p id="faceStatus" class="text-danger"></p>
                <input type="hidden" name="faceData" id="faceData">
            </div>


            <!-- Map -->
            <div id="map" style="height: 400px;"></div>
            <input type="hidden" name="coordinate" id="coordinate">

            <!-- Tombol submit absensi -->
            <div class="mt-3" id="submitContainer" style="display: none;">
                <button type="submit" class="btn btn-secondary">Simpan Kehadiran</button>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            var map = L.map('map').setView([-6.5672482, 107.7482577], 18); // Lokasi PT. Pratama Solusi Teknologi
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            var allowedLatLng = [-6.5672482, 107.7482577]; // Koordinat PT Pratama Solusi Teknologi
            var allowedRadius = 30; // Radius 30 meter yang diizinkan
         
            // Tambahkan lingkaran untuk area yang diizinkan
            var allowedCircle = L.circle(allowedLatLng, {
                color: '#32cd32',
                fillColor: '#32cd32',
                fillOpacity: 0.5,
                radius: allowedRadius // Radius yang diizinkan
            }).addTo(map);

            // Tampilkan SweetAlert untuk meminta izin lokasi
            Swal.fire({
                title: 'Izinkan akses lokasi',
                text: "Kami perlu mengakses lokasi Anda untuk melakukan absensi. Izinkan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Izinkan',
                cancelButtonText: 'Tolak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (navigator.geolocation) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Mendeteksi lokasi Anda...',
                            text: 'Silakan tunggu beberapa saat.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Mulai mendapatkan lokasi setelah izin diberikan oleh pengguna
                        map.locate({
                            setView: true,
                            maxZoom: 16,
                            watch: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Geolokasi tidak didukung oleh browser ini.'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Akses lokasi ditolak',
                        text: 'Anda tidak dapat melakukan absensi tanpa akses lokasi.'
                    });
                }
            });

            function onLocationFound(e) {
                var radius = e.accuracy;

                // Tambahkan marker untuk lokasi pengguna
                var userMarker = L.marker(e.latlng).addTo(map)
                    .bindPopup("Lokasi Anda dalam radius " + radius + " meter.").openPopup();

                // Pusatkan peta pada pengguna
                map.setView(e.latlng, 18);

                // Cek apakah pengguna berada dalam area yang diizinkan
                if (allowedCircle.getBounds().contains(e.latlng)) {
                    document.getElementById('coordinate').value = e.latlng.lat + "," + e.latlng.lng;

                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Ditemukan',
                        text: 'Anda berada di lokasi yang diizinkan untuk absen.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Sembunyikan alert gagal dan tampilkan alert berhasil
                    document.getElementById('outOfAreaAlert').style.display = "none";
                    document.getElementById('inAreaAlert').style.display = "block";

                    document.getElementById('submitContainer').style.display = "block";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Diluar Area yang Diizinkan',
                        text: 'Anda berada di luar lokasi yang diizinkan untuk absen.',
                        confirmButtonColor: '#2c3e50',
                    });

                    // Sembunyikan alert berhasil dan tampilkan alert gagal
                    document.getElementById('inAreaAlert').style.display = "none";
                    document.getElementById('outOfAreaAlert').style.display = "block";

                    document.getElementById('submitContainer').style.display = "none";
                }
            }

            map.on('locationfound', onLocationFound);

            map.on('locationerror', function(e) {
                document.getElementById('locationErrorAlert').style.display = 'block';
                document.getElementById('submitContainer').style.display = "none";

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal mendapatkan lokasi',
                    text: 'Silakan periksa izin lokasi dan coba lagi.',
                    confirmButtonColor: '#2c3e50',
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const captureButton = document.getElementById('captureFace');
            const faceStatus = document.getElementById('faceStatus');
            const faceDataInput = document.getElementById('faceData');

            // Posisikan canvas agar overlay di atas video
            canvas.style.position = "absolute";
            canvas.style.top = "0";
            canvas.style.left = "0";
            canvas.style.width = "100%";
            canvas.style.height = "100%";
            canvas.style.pointerEvents = "none"; // Supaya tidak mengganggu interaksi video

            video.parentElement.appendChild(canvas);

            // Aktifkan kamera
            async function startCamera() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    video.srcObject = stream;
                } catch (error) {
                    Swal.fire('Gagal Mengakses Kamera', 'Mohon izinkan akses kamera di browser.', 'error');
                }
            }

            await startCamera();

            // Load model BlazeFace
            let model;
            try {
                model = await blazeface.load();
                detectFace(); // Mulai deteksi wajah jika model berhasil dimuat
            } catch (error) {
                Swal.fire('Gagal Memuat Model', 'Pastikan Anda memiliki koneksi internet yang stabil.',
                    'error');
                return;
            }

            async function detectFace() {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const predictions = await model.estimateFaces(video, false);

                if (predictions.length > 0) {
                    if (faceStatus.innerText !== 'Wajah terdeteksi!') {
                        faceStatus.innerText = 'Wajah terdeteksi!';
                        faceStatus.classList.remove('text-danger');
                        faceStatus.classList.add('text-success');
                        captureButton.disabled = false; // Aktifkan tombol

                        // SweetAlert jika wajah terdeteksi
                        Swal.fire({
                            icon: 'success',
                            title: 'Wajah terdeteksi!',
                            text: 'Silakan ambil foto.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    predictions.forEach(prediction => {
                        const start = prediction.topLeft;
                        const end = prediction.bottomRight;
                        const size = [end[0] - start[0], end[1] - start[1]];

                        // Gambar kotak hijau di sekitar wajah
                        ctx.strokeStyle = '#00FF00';
                        ctx.lineWidth = 3;
                        ctx.strokeRect(start[0] - 5, start[1] - 5, size[0] + 10, size[1] + 10);
                    });
                } else {
                    if (faceStatus.innerText !== 'Tidak ada wajah yang terdeteksi!') {
                        faceStatus.innerText = 'Tidak ada wajah yang terdeteksi!';
                        faceStatus.classList.remove('text-success');
                        faceStatus.classList.add('text-danger');
                        captureButton.disabled = true; // Nonaktifkan tombol

                        // SweetAlert jika wajah tidak terdeteksi
                        Swal.fire({
                            icon: 'error',
                            title: 'Wajah tidak terdeteksi!',
                            text: 'Pastikan wajah terlihat dengan jelas di kamera.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                }


                requestAnimationFrame(detectFace); // Looping deteksi wajah secara real-time
            }

            // Ambil foto saat tombol ditekan
            captureButton.addEventListener('click', function() {
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                faceDataInput.value = canvas.toDataURL(); // Simpan gambar sebagai base64
                Swal.fire({
                    title: 'Foto Berhasil Diambil!',
                    text: 'Wajah berhasil ditangkap.',
                    icon: 'success',
                    confirmButtonText: 'Oke'
                });
                document.getElementById('submitContainer').style.display = "block";
            });
        });
    </script>
@endsection
