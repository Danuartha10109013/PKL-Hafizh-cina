<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Kehadiran</title>
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/logo-shorcut-kehadiran.png') }}">
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .invoice-brand {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-brand img {
            max-width: 150px;
        }

        .invoice-head {
            margin-bottom: 20px;
        }

        .invoice-contact-info h4,
        .invoice-desc h3 {
            margin: 0;
            color: #333;
        }

        .list-plain {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-plain li {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .list-plain li em {
            color: #555;
            margin-right: 8px;
        }

        #mapPrint {
            height: 300px;
            width: 100%;
            margin-top: 20px;
            border: 1px solid #ccc;
        }

        .attendance-image {
            margin-top: 10px;
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        @media print {
    body {
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .container {
        width: 100%;
        padding: 10mm;
        box-sizing: border-box;
        border: none;
        background: white !important;
    }

    .btn {
        display: none;
    }

    .attendance-image {
        width: 100%;
        max-width: 200px;
        height: auto;
    }

    .row {
        display: flex;
        justify-content: space-between;
        flex-wrap: nowrap;
    }

    .col-md-6 {
        width: 48%;
    }

    #mapPrint {
        height: 250px !important;
        page-break-inside: avoid;
    }
}

    </style>
</head>

<body>
    <div class="container">
        <div class="invoice-brand text-center">
            <img src="{{ asset('demo5/src/images/logo-dark.png') }}"
                srcset="{{ asset('demo5/src/images/logo-dark2x.png 2x') }}" alt="Logo">
        </div>

        <div class="invoice-head">
            <div class="invoice-contact">
                <span class="overline-title">Detail Kehadiran</span>
                <div class="invoice-contact-info">
                    <h4 class="title">Pegawai</h4>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-plain">
                                <li><em class="icon ni ni-calendar-fill fs-18px"></em>Tanggal: {{ $attendance->date }}</li>
                                <li><em class="icon ni ni-clock-fill fs-14px"></em>Waktu: {{ $attendance->time }}</li>
                                <li><em class="icon ni ni-check-circle-fill fs-14px"></em>Status: {{ $attendance->status == 0 ? 'Masuk' : 'Pulang' }}</li>
                                <li><em class="icon ni ni-map-pin-fill fs-14px"></em>Koordinat: {{ $attendance->coordinate }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p>Bukti Foto:</p>
                            <img class="attendance-image" style="width: 40%" src="{{ asset('storage/' . $attendance->image) }}" alt="Bukti Foto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mapPrint"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latitude = {{ $latitude ?? 'null' }};
            var longitude = {{ $longitude ?? 'null' }};

            if (latitude !== null && longitude !== null) {
                var map = L.map('mapPrint').setView([latitude, longitude], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                L.marker([latitude, longitude]).addTo(map)
                    .bindPopup("Lokasi Kehadiran")
                    .openPopup();
            } else {
                document.getElementById('mapPrint').innerText = "Koordinat tidak tersedia.";
            }
        });
    </script>
</body>

</html>
