<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: black;
            color: white;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid black;
            padding-top: 5px;
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    @foreach ( $leaves as $l)
        
        <div class="header">LEAVES SUMMARY</div>
        
        <table>
            <tr>
                <th colspan="2" style="width: 30px">Data Pegawai</th>
            </tr>
            <tr>
                <td style="width: 200px; background-color: rgba(128, 128, 128, 0.2);">Nama</td>
                @php
                    $employe = App\Models\User::find($l->enhancer);
                @endphp
                <td>{{$employe->name}}</td>
            </tr>
            <tr>
                <td style="width: 200px; background-color: rgba(128, 128, 128, 0.2);">No. Induk Pegawai</td>
                <td>{{$employe->id_card}}</td>
            </tr>
            <tr>
                <td style="width: 200px; background-color: rgba(128, 128, 128, 0.2);">Jabatan</td>
                <td>{{$employe->position}}</td>
            </tr>
            <tr>
                <td style="width: 200px; background-color: rgba(128, 128, 128, 0.2);">No. Handphone</td>
                <td>{{$employe->telephone}}</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th>Jenis Cuti</th>
                <th colspan="2">Periode Cuti</th>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" {{ $l->category == "annual" ? 'checked' : '' }}> Cuti Tahunan
                </td>
                <td style="width: 150px; background-color: rgba(128, 128, 128, 0.2);">Diajukan Tgl.</td>
                <td>{{($l->created_at->format("d-m-Y"))}}</td>
            </tr>
            <tr>
                
                <td><input type="checkbox" {{ $l->category != "annual" ? 'checked' : '' }}> Cuti Lainnya</td>
                <td style="width: 150px; background-color: rgba(128, 128, 128, 0.2);">Tgl. Mulai Cuti</td>
                <td>{{ \Carbon\Carbon::parse($l->date)->format("d-m-Y") }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 150px; background-color: rgba(128, 128, 128, 0.2);">Tgl. Selesai Cuti</td>
                <td>{{ \Carbon\Carbon::parse($l->end_date)->format("d-m-Y") }}</td>

            </tr>
            <tr>
                <td></td>
                <td style="width: 150px; background-color: rgba(128, 128, 128, 0.2);">Lama Cuti</td>
                <td>{{ \Carbon\Carbon::parse($l->date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) }} hari</td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 150px; background-color: rgba(128, 128, 128, 0.2);">Tgl. Masuk</td>
                <td>{{ \Carbon\Carbon::parse($l->end_date)->addDay()->format("d-m-Y") }}</td>
            </tr>
            
            
        </table>
        
        <table>
            <tr>
                <th>Keterangan / Alasan</th>
            </tr>
            <tr>
                <td>{{$l->reason}} <br>
                    {{$l->subcategory}}</td>

            </tr>
        </table>
        
        <table>
            <tr>
                <th>Catatan Lainnya</th>
            </tr>
            <tr>
                <td>{{$l->reason_verification}}</td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="text-align: center;">Diajukan Oleh</th>
                <th style="text-align: center;">Disetujui Oleh</th>
            </tr>
            <tr>
                <td style="text-align: center;">
                    Purwakarta, {{$l->created_at->format("d-m-Y")}}<br>
                <br><br>
                <br><br>
                <br><br>
                <span>{{$employe->name}}</span><br>
                <hr style="background-color: black; height: 3px; width: 30%; border: none;">
                <span>{{$employe->position}}</span>
                </td>
                <td style="text-align: center;">
                    Purwakarta, {{$l->updated_at->format("d-m-Y")}}<br>
                <br><br>
                <br><br>
                <br><br>
                <span>Nama</span><br>
                <hr style="background-color: black; height: 3px; width: 30%; border: none;">
                <span>Jabatan</span>
                </td>
            </tr>
        </table>
    @endforeach
    
    
</body>
</html>
