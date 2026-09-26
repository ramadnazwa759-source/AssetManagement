<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori - Kalisawah</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #1e293b;
        }

        .container {
            width: 600px;
            max-width: 90%;
            margin: 60px auto;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        h1 {
            margin-bottom: 8px;
            color: #0b4f9c;
        }

        .description {
            color: #64748b;
            margin-bottom: 25px;
        }

        .info {
            background: #f8fafc;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            border-color: #0b4f9c;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 11px 18px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-save {
            background: #0b4f9c;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Ubah Kategori Aset</h1>

        <p class="description">
            Perbarui informasi kategori aset Kalisawah.
        </p>

        <div class="info">
            ID Kategori:
            <strong>{{ $kategori->id_kategori }}</strong>
        </div>

        <form
            action="{{ route('kategori.update', $kategori->id_kategori) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label for="nama_kategori">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    placeholder="Masukkan nama kategori"
                    required>

                @error('nama_kategori')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <div class="form-group">

                <label for="Deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="Deskripsi"
                    name="Deskripsi"
                    placeholder="Masukkan deskripsi kategori">{{ old('Deskripsi', $kategori->Deskripsi) }}</textarea>

                @error('Deskripsi')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <div class="actions">

                <a
                    href="{{ route('kategori.index') }}"
                    class="btn btn-cancel">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-save">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>