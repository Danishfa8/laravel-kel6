<!DOCTYPE html>
<html>
<head>
    <title>Data Pajak</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }
    </style>
    <center>
        <h4>Data Pajak</h4>
    </center>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>                
                <th>Kode Pajak</th>
                <th>Nama Pajak</th>
                <th>Jenis Pajak</th>
                <th>Deskripsi</th>
                <th>Tarif Pajak</th>
                <th>Tanggal Berlaku</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>                    
                    <td>{{ $item->kode_pajak }}</td>
                    <td>{{ $item->nama_pajak }}</td>
                    <td>{{ $item->jenis_pajak }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ 'Rp ' . number_format($item->tarif_pajak, 0, ',', '.') }}</td>
                    <td>{{ $item->tanggal_berlaku }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
