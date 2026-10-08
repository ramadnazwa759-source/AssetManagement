@extends('layouts.app')

@section('content')

<div class="create-page">

    <div class="page-header">

        <div class="header-left">

            <a href="{{ route(
                'kategori.sub-kategori.index',
                $kategori->id_kategori
            ) }}"
               class="back-button">
                ←
            </a>

            <div>

                <span class="page-eyebrow">
                    MASTER DATA
                </span>

                <h1>Edit Sub Kategori</h1>

                <p>
                    Ubah data sub kategori
                    <strong>{{ $subKategori->nama_sub_kategori }}</strong>.
                </p>

            </div>

        </div>

    </div>


    <div class="form-card">

        <form action="{{ route(
            'kategori.sub-kategori.update',
            [
                'id_kategori' => $kategori->id_kategori,
                'id' => $subKategori->id_sub_kategori
            ]
        ) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="form-group">

                <label>ID Sub Kategori</label>

                <input type="text"
                       value="{{ $subKategori->id_sub_kategori }}"
                       readonly>

            </div>


            <div class="form-group">

                <label>Kategori</label>

                <input type="text"
                       value="{{ $kategori->nama_kategori }}"
                       readonly>

            </div>


            <div class="form-group">

                <label for="nama_sub_kategori">
                    Nama Sub Kategori
                    <span>*</span>
                </label>

                <input type="text"
                       id="nama_sub_kategori"
                       name="nama_sub_kategori"
                       value="{{ old(
                           'nama_sub_kategori',
                           $subKategori->nama_sub_kategori
                       ) }}"
                       required>

                @error('nama_sub_kategori')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="form-group">

                <label for="gambar">
                    Gambar
                </label>

                @if ($subKategori->gambar)

                    <div class="current-image">

                        <img src="{{ asset(
                            'storage/' . $subKategori->gambar
                        ) }}"
                             alt="Gambar Sub Kategori">

                        <span>
                            Gambar saat ini
                        </span>

                    </div>

                @endif

                <input type="file"
                       id="gambar"
                       name="gambar"
                       accept=".jpg,.jpeg,.png,.webp">

                @error('gambar')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

                <small class="form-help">
                    Kosongkan jika tidak ingin mengganti gambar.
                </small>

            </div>


            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="5">{{ old(
                              'deskripsi',
                              $subKategori->deskripsi
                          ) }}</textarea>

                @error('deskripsi')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="form-actions">

                <a href="{{ route(
                    'kategori.sub-kategori.index',
                    $kategori->id_kategori
                ) }}"
                   class="cancel-button">
                    Batal
                </a>

                <button type="submit"
                        class="save-button">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


@push('styles')

<style>

.create-page {
    padding: 30px;
    max-width: 900px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 25px;
}

.header-left {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.back-button {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    border: 1px solid #e5e7eb;
    color: #2563eb;
    text-decoration: none;
    font-size: 20px;
}

.page-eyebrow {
    color: #2563eb;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
}

.page-header h1 {
    margin: 5px 0;
    color: #111827;
}

.page-header p {
    margin: 0;
    color: #6b7280;
}

.form-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
}

.form-group label span {
    color: #dc2626;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.form-group input[readonly] {
    background: #f9fafb;
    color: #6b7280;
}

.current-image {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.current-image img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #e5e7eb;
}

.current-image span {
    color: #6b7280;
    font-size: 13px;
}

.form-help {
    display: block;
    margin-top: 6px;
    color: #9ca3af;
    font-size: 12px;
}

.input-error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.cancel-button,
.save-button {
    padding: 11px 18px;
    border-radius: 7px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
}

.cancel-button {
    background: #f3f4f6;
    color: #374151;
}

.save-button {
    border: none;
    background: #2563eb;
    color: white;
}

</style>

@endpush

@endsection