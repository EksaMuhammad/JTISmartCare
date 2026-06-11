<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body{
            font-family: DejaVu Sans;
            font-size:12px;
        }

        h2{
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#f0f0f0;
        }
    </style>
</head>
<body>

<h2>
LAPORAN MONITORING BURNOUT MAHASISWA
</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>Kategori</th>
            <th>Risk Index</th>
            <th>Tanggal</th>
        </tr>
    </thead>

    <tbody>

        @foreach($data as $item)
        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>{{ $item->user->name ?? '-' }}</td>

            <td>{{ $item->user->nim ?? '-' }}</td>

            <td>{{ $item->user->jurusan ?? '-' }}</td>

            <td>{{ $item->kategori_risiko }}</td>

            <td>{{ $item->risk_index }}%</td>

            <td>
                {{ $item->created_at->format('d-m-Y') }}
            </td>

        </tr>
        @endforeach

    </tbody>
</table>

</body>
</html>
