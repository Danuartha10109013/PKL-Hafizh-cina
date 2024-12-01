<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laporan Kehadiran Pegawai">
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/faviconlogo.png') }}">
    <!-- Page Title -->
    <title>Cetak Kehadiran Pegawai</title>
    <!-- StyleSheets -->
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                font-size: 12px;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
            }

            .badge {
                color: #fff !important;
                padding: 5px 10px !important;
            }

            .bg-success {
                background-color: #28a745 !important;
            }

            .bg-danger {
                background-color: #dc3545 !important;
            }

            .btn,
            .navbar,
            footer {
                display: none !important;
            }
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .invoice-header h4 {
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .invoice-header p {
            margin: 0;
            font-size: 0.9rem;
        }

        .table th {
            background-color: #f8f9fa;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }
    </style>
</head>

<body class="bg-white" onload="window.print()">
    <div class="nk-block">
        <div class="invoice invoice-print">
            <div class="invoice-wrap">
                <!-- Logo dan Header -->
                <div class="invoice-header">
                    <img src="{{ asset('demo5/src/images/logonew1.png') }}" alt="Logo">
                    <h4>Laporan Kehadiran Pegawai</h4>
                    <p>
                        Periode:
                        @if (request()->input('printOption') === 'byDate' && request()->has('date'))
                            {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') }}
                        @elseif (request()->input('printOption') === 'byMonth' && request()->has('month'))
                            {{ \Carbon\Carbon::parse(request('month') . '-01')->translatedFormat('F Y') }}
                        @elseif (request()->input('printOption') === 'byYear' && request()->has('year'))
                            Tahun {{ request('year') }}
                        @else
                            Semua Periode
                        @endif
                    </p>
                </div>

                <!-- Tabel Data Kehadiran -->
                <div class="invoice-bills">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Lebih Awal</th>
                                    <th>Terlambat</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $key => $attendance)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $attendance->time ?? '-' }}</td>
                                        <td>{{ $attendance->early_leave ?? '-' }}</td>
                                        <td>{{ $attendance->late ?? '-' }}</td>
                                        <td>{{ $attendance->coordinate ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data kehadiran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div><!-- .invoice-bills -->
            </div><!-- .invoice-wrap -->
        </div><!-- .invoice -->
    </div><!-- .nk-block -->
</body>

</html>
