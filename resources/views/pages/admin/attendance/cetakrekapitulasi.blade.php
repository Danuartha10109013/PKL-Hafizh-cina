<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/faviconlogo.png') }}">
    <!-- Page Title  -->
    <title>Cetak Rekapitulasi</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">
</head>

<body class="bg-white" onload="printPromot()">
    <div class="nk-block">
        <div class="invoice invoice-print">
            <div class="invoice-wrap">
                <div class="invoice-brand text-center">
                    <img src="{{ asset('demo5/src/images/logonew1.png') }}" alt="Attendance Logo">
                </div>
                <div class="invoice-head">
                    <div class="invoice-contact">
                        <span class="overline-title">Report</span>
                        <div class="invoice-contact-info">
                            <h4 class="title">Rekapitulasi Kehadiran Pegawai</h4>
                            <p class="text-muted">
                                Bulan: {{ $month }}, Tahun: {{ $year }}
                            </p>
                        </div>
                    </div>
                </div><!-- .invoice-head -->
                <div class="invoice-bills mt-4">
                    <div class="table-responsive">
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Nama Pegawai</th>
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                    <th>Pulang Lebih Awal</th>
                                    <th>Masuk Terlambat</th>
                                    <th>Tidak Masuk</th>
                                    <th>Cuti</th>
                                    <th>Sanksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekapData as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data['nama'] }}</td>
                                        <td>{{ $data['masuk'] }}</td>
                                        <td>{{ $data['pulang'] }}</td>
                                        <td>{{ $data['lebih_awal'] }}</td>
                                        <td>{{ $data['terlambat'] }}</td>
                                        <td>{{ $data['tidak_hadir'] }}</td>
                                        <td>{{ $data['cuti'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $data['sanksi'] }}">
                                                {{ $data['sanksi_label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>





                        </table>
                    </div>
                </div><!-- .invoice-bills -->
            </div><!-- .invoice-wrap -->
        </div><!-- .invoice -->
    </div><!-- .nk-block -->
    <script>
        function printPromot() {
            window.print();
        }
    </script>
</body>

</html>
