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
                <button style="display: none" id="captureFace" class="btn btn-primary mt-2" type="button" disabled>Ambil Foto</button>
                <p id="faceStatus" class="text-danger"></p>
                <input type="hidden" name="faceData" id="faceData">
                
            </div>
            <div id="instructions" class="alert alert-info">
                <strong>Instruksi:</strong> Untuk verifikasi wajah, silakan lakukan hal berikut:
                <ul>
                    <li><strong>Berkedip</strong> minimal satu kali</li>
                    <li><strong>Mengangguk</strong> (gerakkan kepala ke bawah cepat)</li>
                    <li><strong>Buka mulut</strong> sedikit</li>
                </ul>
                Setelah semua terdeteksi, foto akan diambil secara otomatis.
            </div>
            <!-- Preview Image Section -->
            <div class="mb-4" id="imagePreview" style="display: none; margin-top: 20px;">
                <h3>Preview Gambar</h3>
                <img id="previewImage" src="" alt="Preview Image" style="max-width: 100%;"/>
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
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            -6.9074944, 107.610112
            var map = L.map('map').setView([-6.5529055, 107.8073251], 18); // Lokasi PT. Pratama Solusi Teknologi
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);
            // -6.55286,107.8061476
            var allowedLatLng = [-6.5529055, 107.8073251];  // Koordinat PT Pratama Solusi Teknologi
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
    document.addEventListener('DOMContentLoaded', async function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const captureButton = document.getElementById('captureFace');
    const faceStatus = document.getElementById('faceStatus');
    const faceDataInput = document.getElementById('faceData');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    canvas.width = 640;
    canvas.height = 480;

    // Aktifkan kamera
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (error) {
            Swal.fire('Gagal Mengakses Kamera', 'Mohon izinkan akses kamera di browser.', 'error');
        }
    }

    await startCamera();

    // Setup FaceMesh
    const faceMesh = new FaceMesh({
        locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`
    });

    faceMesh.setOptions({
        maxNumFaces: 1,
        refineLandmarks: true,
        minDetectionConfidence: 0.5,
        minTrackingConfidence: 0.5,
    });

    let blinked = false;
    let nodded = false;
    let mouthOpened = false;
    let lastNoseY = null;
    let allPassed = false;

    function distance(a, b) {
        return Math.sqrt((a.x - b.x) ** 2 + (a.y - b.y) ** 2);
    }

    faceMesh.onResults((results) => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (results.multiFaceLandmarks.length > 0) {
            faceStatus.innerText = "Wajah terdeteksi!";
            faceStatus.classList.remove("text-danger");
            faceStatus.classList.add("text-success");
            captureButton.disabled = false;

            const landmarks = results.multiFaceLandmarks[0];

            // Gambar titik landmark
            for (const point of landmarks) {
                const x = point.x * canvas.width;
                const y = point.y * canvas.height;
                ctx.beginPath();
                ctx.arc(x, y, 1, 0, 2 * Math.PI);
                ctx.fillStyle = 'lime';
                ctx.fill();
            }

            // Deteksi kedipan mata
            const leftEAR = distance(landmarks[159], landmarks[145]) / distance(landmarks[33], landmarks[133]);
            const rightEAR = distance(landmarks[386], landmarks[374]) / distance(landmarks[362], landmarks[263]);
            const ear = (leftEAR + rightEAR) / 2;
            if (ear < 0.2) {
                blinked = true;
            }

            // Deteksi buka mulut
            const mar = distance(landmarks[13], landmarks[14]) / distance(landmarks[78], landmarks[308]);
            if (mar > 0.05) {
                mouthOpened = true;
            }

            // Deteksi anggukan
            const noseY = landmarks[1].y;
            if (lastNoseY !== null && noseY - lastNoseY > 0.02) {
                nodded = true;
            }
            lastNoseY = noseY;

            if (blinked && nodded && mouthOpened && !allPassed) {
                allPassed = true;
                faceStatus.innerText = "Verifikasi Humanisasi Berhasil!";
                Swal.fire({
                    icon: 'success',
                    title: 'Aksi Terdeteksi!',
                    text: 'Berkedip, Mengangguk, dan Buka Mulut berhasil.',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    // Capture the image after 1 second delay
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL(); // Save as base64
                    faceDataInput.value = imageData;

                    // Show preview of captured image
                    previewImage.src = imageData; // Set the preview image source
                    imagePreview.style.display = 'block'; // Show the preview section

                    Swal.fire({
                        title: 'Foto Diambil!',
                        text: 'Wajah berhasil ditangkap otomatis.',
                        icon: 'success',
                        confirmButtonText: 'Oke'
                    });

                    document.getElementById('submitContainer').style.display = "block"; // Optionally show submit button
                }, 1000); // Wait for 1 second before capturing
            }

        } else {
            faceStatus.innerText = "Tidak ada wajah yang terdeteksi!";
            faceStatus.classList.remove("text-success");
            faceStatus.classList.add("text-danger");
            captureButton.disabled = true;
        }
    });

    // Inisialisasi kamera ke FaceMesh
    const camera = new Camera(video, {
        onFrame: async () => {
            await faceMesh.send({ image: video });
        },
        width: 640,
        height: 480
    });
    camera.start();

    // Tombol manual tetap bisa digunakan jika mau
    captureButton.addEventListener('click', function () {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        faceDataInput.value = canvas.toDataURL();
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
