<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Kode Pajak</th>
                <th>Nama Pajak</th>
                <th>Jenis Pajak</th>
            </tr>
        </thead>
    
        <tbody class="table-group-divider">
            @foreach($jenis_pajak as $item)
            <tr>
                <td>{{ $item->kode_pajak }}</td>
                <td>{{ $item->nama_pajak }}</td>
                <td><a href="{{ route('jenis_pajak', str_replace(' ', '_', strtolower($item->jenis_pajak))) }}"
                        class="btn btn-primary m-2"> <i class="bi bi-search"></i> Cari</a></td>
            </tr>
            @endforeach
        </tbody>
            </table>
</body>
</html>
