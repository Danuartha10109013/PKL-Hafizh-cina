<!DOCTYPE html>
<html lang="id">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/faviconlogo.png') }}">

    <title>Cetak Absensi - {{ $name }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="./assets/css/libs/fontawesome-icons.css">
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            font-size: 14px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 4px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f4f6f9;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary {
            margin-top: 30px;
        }

        .summary h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Data Absensi Pegawai</h1>
        <p><strong>Nama:</strong> {{ $name }}</p>
        <p><strong>Bulan:</strong> {{ request('month') }} | <strong>Tahun:</strong> {{ request('year') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Waktu Masuk</th>
                <th>Waktu Pulang</th>
                <th>Koordinat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                    <td>
                        {{ $item->status == '0' ? \Carbon\Carbon::parse($item->time)->format('H:i:s') : '-' }}
                    </td>
                    <td>
                        {{ $item->status == '1' ? \Carbon\Carbon::parse($item->time)->format('H:i:s') : '-' }}
                    </td>
                    <td>{{ $item->coordinate }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data absensi untuk bulan dan tahun yang
                        dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Absensi</h3>
        <table>
            <thead>
                <tr>
                    <th>Total Terlambat</th>
                    <th>Total Tidak Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $terlambat }}</td>
                    <td>{{ $tidakMasuk }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</html>
