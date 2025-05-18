<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi - {{ $name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Data Absensi</h1>
        <p>Nama: {{ $name }}</p>
        <p>Bulan: {{ request('month') }} | Tahun: {{ request('year') }}</p>
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
            @forelse ($attendance as $key => $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
</td>
                    <td>
                        @if ($item->status == '0')
                            {{ \Carbon\Carbon::parse($item->time)->format('H:i:s') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($item->status == '1')
                            {{ \Carbon\Carbon::parse($item->time)->format('H:i:s') }}
                        @else
                            -
                        @endif
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
    <p>Summary</p>
    <table>
        <thead>
            <tr>
                <th>Total Terlambat</th>
                <th>Total Tidak Masuk</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$terlambat}}</td>
                <td>{{$tidakMasuk}}</td>
            </tr>
                
        </tbody>
    </table>
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>
