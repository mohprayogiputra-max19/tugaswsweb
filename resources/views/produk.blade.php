HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Katalog Produk: {{ $kategori }}</h1>
    <table border="1" cellpadding="8" cellspacing="0">
    <thead>
    <tr>
    <th>ID</th>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($daftarProduk as $item)
    <tr>
    <td>{{ $item['id'] }}</td>
    <td>{{ $item['nama'] }}</td>
    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
    <td><a href="{{ url('/produk/' . $item['id']) }}">Lihat Detail</a></td>
    </tr>
    @endforeach
    </tbody>
    </table>
</body>
</html>