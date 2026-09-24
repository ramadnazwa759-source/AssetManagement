<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
</head>

<body>

<h1>Edit Kategori</h1>

@if($errors->any())
    <div>
        @foreach($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form
    action="{{ route('kategori.update', $kategori->id_kategori) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <div>
        <label>Nama Kategori</label>
        <br>

        <input
            type="text"
            name="nama_kategori"
            value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
            required
        >
    </div>

    <br>

    <div>
        <label>Deskripsi</label>
        <br>

        <textarea name="Deskripsi">{{ old('Deskripsi', $kategori->Deskripsi) }}</textarea>
    </div>

    <br>

    <button type="submit">
        Update
    </button>

    <a href="{{ route('kategori.index') }}">
        Kembali
    </a>

</form>

</body>
</html>