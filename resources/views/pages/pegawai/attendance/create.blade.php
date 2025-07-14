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
                <button style="display: none" id="captureFace" class="btn btn-primary mt-2" type="button" disabled>Ambil
                    Foto</button>
                <p id="faceStatus" class="text-danger"></p>
                <input type="hidden" name="faceData" id="faceData">

            </div>
            <div id="instructions" class="alert alert-info">
                <div id="faceStatus" class="mb-2 text-danger">Menunggu deteksi wajah...</div>
                <div id="faceInstruction" class="mb-3 fw-bold text-primary">Silakan mulai...</div>

                <strong>Instruksi:</strong> Untuk verifikasi wajah, silakan lakukan hal berikut: <br>
                <input type="checkbox" id="checkBlink" disabled> Berkedip<br>
                <input type="checkbox" id="checkNod" disabled> Mengangguk<br>
                <input type="checkbox" id="checkMouth" disabled> Buka Mulut<br>

                Setelah semua terdeteksi, foto akan diambil secara otomatis.
            </div>
            <!-- Preview Image Section -->
            <div class="mb-4" id="imagePreview" style="display: none; margin-top: 20px;">
                <h3>Preview Gambar</h3>
                <img id="previewImage" src="" alt="Preview Image" style="max-width: 100%;" />
            </div>



            <!-- Map -->
            <div id="map" style="height: 400px; width: 100%;"></div>
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
    @php
        $kordinat = \App\Models\Koordinat::find(1);
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([{{ $kordinat->latitude }}, {{ $kordinat->longitude }}], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            // Lingkaran radius area absensi
            var allowedLatLng = [{{ $kordinat->latitude }}, {{ $kordinat->longitude }}];
            var allowedRadius = 300;

            var allowedCircle = L.circle(allowedLatLng, {
                color: '#32cd32',
                fillColor: '#32cd32',
                fillOpacity: 0.4,
                radius: allowedRadius
            }).addTo(map).bringToBack(); // <== langsung tambah circle setelah tileLayer

            map.fitBounds(allowedCircle.getBounds());
            console.log("Latitude & Longitude Circle:", allowedLatLng);

            // Marker merah lokasi perusahaan
            var redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });

            L.marker(allowedLatLng, {
                icon: redIcon
            }).addTo(map).bindPopup("Lokasi Perusahaan");

            // Marker merah untuk lokasi perusahaan
            var redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            });
            L.marker(allowedLatLng, {
                icon: redIcon
            }).addTo(map).bindPopup("Lokasi Perusahaan");

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

                var userLatLng = e.latlng;
                var companyLatLng = L.latLng({{ $kordinat->latitude }}, {{ $kordinat->longitude }});

                // Set view ke lokasi pegawai
                map.setView(userLatLng, 18);

                // Marker biru untuk lokasi user/pegawai
                var blueIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });

                var userMarker = L.marker(userLatLng, {
                    icon: blueIcon
                }).addTo(map).bindPopup("Lokasi Anda").openPopup();

                // Garis penghubung antara perusahaan dan pegawai
                var connectingLine = L.polyline([companyLatLng, userLatLng], {
                    color: 'green',
                    weight: 3,
                    opacity: 0.7,
                    dashArray: '6, 6'
                }).addTo(map);

                // Isi input koordinat untuk dikirim ke server
                document.getElementById('coordinate').value = userLatLng.lat + "," + userLatLng.lng;
                console.log("Company LatLng:", companyLatLng);
                console.log("User LatLng:", userLatLng);
                // Cek apakah dalam radius
                if (companyLatLng.distanceTo(userLatLng) <= allowedRadius) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Ditemukan',
                        text: 'Anda berada di lokasi yang diizinkan untuk absen.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    document.getElementById('outOfAreaAlert').style.display = "none";
                    document.getElementById('inAreaAlert').style.display = "block";
                    document.getElementById('submitContainer').style.display = "none";

                    startFaceVerification();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Diluar Area yang Diizinkan',
                        text: 'Anda berada di luar lokasi yang diizinkan untuk absen.',
                        confirmButtonColor: '#2c3e50',
                    });

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

            // Fungsi verifikasi wajah humanisasi
            async function startFaceVerification() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const ctx = canvas.getContext('2d');
                const faceStatus = document.getElementById('faceStatus');
                const instruction = document.getElementById('faceInstruction');
                const faceDataInput = document.getElementById('faceData');
                const imagePreview = document.getElementById('imagePreview');
                const previewImage = document.getElementById('previewImage');
                canvas.width = 640;
                canvas.height = 480;

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    video.srcObject = stream;
                } catch (error) {
                    Swal.fire('Gagal Mengakses Kamera', 'Mohon izinkan akses kamera di browser.', 'error');
                    return;
                }

                const faceMesh = new FaceMesh({
                    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`
                });

                faceMesh.setOptions({
                    maxNumFaces: 1,
                    refineLandmarks: true,
                    minDetectionConfidence: 0.5,
                    minTrackingConfidence: 0.5,
                });

                let step = 0; // 0: Mouth, 1: Nod, 2: Blink
                let lastNoseY = null;

                function distance(a, b) {
                    return Math.sqrt((a.x - b.x) ** 2 + (a.y - b.y) ** 2);
                }

                function updateInstruction() {
                    const messages = [
                        "Silakan BUKA MULUT terlebih dahulu.",
                        "Bagus! Sekarang SILAKAN MENGANGGUK.",
                        "Terakhir, SILAKAN BERKEDIP.",
                    ];
                    instruction.innerText = messages[step] || "";
                }

                updateInstruction();

                faceMesh.onResults((results) => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    if (results.multiFaceLandmarks.length > 0) {
                        faceStatus.innerText = "Wajah terdeteksi!";
                        faceStatus.classList.remove("text-danger");
                        faceStatus.classList.add("text-success");

                        const landmarks = results.multiFaceLandmarks[0];

                        // Gambar titik wajah
                        for (const point of landmarks) {
                            const x = point.x * canvas.width;
                            const y = point.y * canvas.height;
                            ctx.beginPath();
                            ctx.arc(x, y, 1, 0, 2 * Math.PI);
                            ctx.fillStyle = 'lime';
                            ctx.fill();
                        }

                        const leftEAR = distance(landmarks[159], landmarks[145]) / distance(landmarks[
                            33], landmarks[133]);
                        const rightEAR = distance(landmarks[386], landmarks[374]) / distance(landmarks[
                            362], landmarks[263]);
                        const ear = (leftEAR + rightEAR) / 2;

                        const mar = distance(landmarks[13], landmarks[14]) / distance(landmarks[78],
                            landmarks[308]);

                        const noseY = landmarks[1].y;

                        // === Langkah 1: Buka Mulut ===
                        if (step === 0 && mar > 0.5) {
                            step++;
                            setTimeout(() => {
                                updateInstruction();
                            }, 3000);
                            Swal.fire('Bagus!', 'Buka mulut berhasil terdeteksi.', 'success');
                        }

                        // === Langkah 2: Angguk ===
                        if (step === 1 && lastNoseY !== null && Math.abs(noseY - lastNoseY) > 0.010) {
                            step++;
                            setTimeout(() => {
                                updateInstruction();
                            }, 3000);
                            Swal.fire('Bagus!', 'Gerakan mengangguk berhasil terdeteksi.', 'success');
                        }

                        lastNoseY = noseY;

                        // === Langkah 3: Berkedip ===
                        if (step === 2 && ear < 0.10) {
                            step++;
                            instruction.innerText = "Semua langkah berhasil!";
                            faceStatus.innerText = "Verifikasi Humanisasi Berhasil!";
                            Swal.fire({
                                icon: 'success',
                                title: 'Verifikasi Lengkap!',
                                text: 'Buka Mulut, Mengangguk, dan Berkedip berhasil.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                const imageData = canvas.toDataURL();
                                faceDataInput.value = imageData;

                                if (previewImage) {
                                    previewImage.src = imageData;
                                    if (imagePreview) imagePreview.style.display = 'block';
                                }

                                document.getElementById('submitContainer').style.display =
                                    "block";
                            }, 1000);
                        }

                    } else {
                        faceStatus.innerText = "Tidak ada wajah yang terdeteksi!";
                        faceStatus.classList.remove("text-success");
                        faceStatus.classList.add("text-danger");
                    }
                });

                const camera = new Camera(video, {
                    onFrame: async () => {
                        await faceMesh.send({
                            image: video
                        });
                    },
                    width: 640,
                    height: 480
                });
                camera.start();
            }



        });
    </script>
@endsection
