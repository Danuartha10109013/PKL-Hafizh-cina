<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan SP-{{ $peringatan->status }}</title>
    <style>
        body { font-family: sans-serif; }
        .content { margin-top: 20px; }
        .signature { margin-top: 60px; }
    </style>
</head>
<body>
    @if ($peringatan->status == 1)
    <h2 style="text-align:center;">SURAT PERINGATAN KESATU (SP-{{ $peringatan->status }})</h2>
    @elseif ($peringatan->status == 2)
    <h2 style="text-align:center;">SURAT PERINGATAN KEDUA (SP-{{ $peringatan->status }})</h2>
    @elseif ($peringatan->status == 3)
    <h2 style="text-align:center;">SURAT PERINGATAN KETIGA (SP-{{ $peringatan->status }})</h2>
    @endif

    <p>PT.XYZ</p>

    <div class="content">
        <p><strong>Surat peringatan ini ditujukan kepada:</strong></p>
        <p>
            Nama &emsp;&emsp;: {{ $user->name }} <br>
            NIP &emsp;&emsp; : {{ $user->nip }} <br>
            Jabatan &emsp;&emsp;: {{ $user->position }}
        </p>
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

        <p>
            Sehubungan surat ini, perusahaan memberikan surat peringatan kedua (SP-{{ $peringatan->status }}) sebagai bentuk teguran atas
            pelanggaran disiplin kerja yang telah dilakukan. Namun, Saudara/i tidak segera memberikan respon positif dan
            melakukan perbaikan atasnya.
        </p>

        <p>
            Supaya Saudara/i dapat memperbaiki sikap dan bekerja dengan profesional, maka perusahaan
            menjatuhkan sanksi berdasarkan aturan yang berlaku dan disepakati, yakni:
        </p>

        <ul>
            <li>Tanggal : {{ \Carbon\Carbon::parse($peringatan->created_at)->format('d M Y') }}</li>
            <li>Apabila teguran SP-{{ $peringatan->status }} ini tidak ditanggapi dengan baik, maka dengan terpaksa perusahaan
                akan menindaklanjuti sebagaimana kelayakan perusahaan.
            </li>
        </ul>

        <p>
            Demikian Surat Peringatan {{ $peringatan->status }} ini diterbitkan agar dilaksanakan sebagaimana mestinya. Diharapkan
            Saudara/i dapat memperbaiki sikap dan mampu menunjukkan sikap profesionalisme dalam kedisiplinan.
        </p>

        <div class="signature">
            <p>Dengan hormat,</p>
            <img src="{{$qrImage}}" alt="QR Code" width="150">
            <p>Admin</p>
            
        </div>
    </div>
</body>
</html>
