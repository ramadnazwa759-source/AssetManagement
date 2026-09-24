<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
</head>

<body>

<h1>Tambah Kategori</h1>

@if($errors->any())
    <div>
        @foreach($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('kategori.store') }}" method="POST">

    @csrf

    <div>
        <label>Nama Kategori</label>
        <br>
        <input
            type="text"
            name="nama_kategori"
            value="{{ old('nama_kategori') }}"
            required
        >
    </div>

    <br>

    <div>
        <label>Deskripsi</label>
        <br>
        <textarea name="Deskripsi">{{ old('Deskripsi') }}</textarea>
    </div>

    <br>

    <button type="submit">
        Simpan
    </button>

    <a href="{{ route('kategori.index') }}">
        Kembali
    </a>

</form>

</body>
</html>