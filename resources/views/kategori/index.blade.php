<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Aset</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #f0ad00;
        }

        .btn-delete {
            background: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #0066cc;
            color: white;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .error {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Kategori Aset</h1>

    {{-- Pesan berhasil --}}
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol tambah --}}
    <a href="{{ route('kategori.create') }}" class="btn">
        + Tambah Kategori
    </a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kategori</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($kategori as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $item->id_kategori }}
                    </td>

                    <td>
                        {{ $item->nama_kategori }}
                    </td>

                    <td>
                        {{ $item->Deskripsi ?? '-' }}
                    </td>

                    <td>

                        {{-- Edit --}}
                        <a
                            href="{{ route('kategori.edit', $item->id_kategori) }}"
                            class="btn btn-edit"
                        >
                            Edit
                        </a>

                        {{-- Hapus --}}
                        <form
                            action="{{ route('kategori.destroy', $item->id_kategori) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-delete"
                                onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                            >
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="5" style="text-align:center;">
                        Belum ada data kategori.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

</div>

</body>
</html>