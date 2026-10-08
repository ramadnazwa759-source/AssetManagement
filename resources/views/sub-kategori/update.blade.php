@extends('layouts.app')

@section('content')

<div class="update-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div class="header-left">

            <a
                href="{{ route(
                    'kategori.sub-kategori.index',
                    $kategori->id_kategori
                ) }}"
                class="back-button"
                title="Kembali ke Sub Kategori"
            >
                ←
            </a>

            <div>

                <span class="page-eyebrow">
                    MASTER DATA
                </span>

                <h1>Ubah Sub Kategori</h1>

                <p>
                    Ubah data sub kategori
                    <strong>{{ $subKategori->nama_sub_kategori }}</strong>.
                </p>

            </div>

        </div>

    </div>


    {{-- FORM --}}
    <div class="form-card">

        <form
            action="{{ route(
                'kategori.sub-kategori.update',
                [
                    'id_kategori' => $kategori->id_kategori,
                    'id' => $subKategori->id_sub_kategori
                ]
            ) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')


            {{-- ID SUB KATEGORI --}}
            <div class="form-group">

                <label for="id_sub_kategori">
                    ID Sub Kategori
                </label>

                <input
                    type="text"
                    id="id_sub_kategori"
                    value="{{ $subKategori->id_sub_kategori }}"
                    readonly
                >

            </div>


            {{-- KATEGORI --}}
            <div class="form-group">

                <label for="kategori">
                    Kategori
                </label>

                <input
                    type="text"
                    id="kategori"
                    value="{{ $kategori->nama_kategori }}"
                    readonly
                >

            </div>


            {{-- NAMA SUB KATEGORI --}}
            <div class="form-group">

                <label for="nama_sub_kategori">
                    Nama Sub Kategori
                    <span>*</span>
                </label>

                <input
                    type="text"
                    id="nama_sub_kategori"
                    name="nama_sub_kategori"
                    value="{{ old(
                        'nama_sub_kategori',
                        $subKategori->nama_sub_kategori
                    ) }}"
                    required
                >

                @error('nama_sub_kategori')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- GAMBAR --}}
            <div class="form-group">

                <label for="gambar">
                    Gambar
                </label>

                @if ($subKategori->gambar)

                    <div class="current-image">

                        @if ($subKategori->gambar)

                        <div class="current-image">

                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($subKategori->gambar) }}"
                                alt="Gambar {{ $subKategori->nama_sub_kategori }}"
                            >

                            <div class="current-image-info">

                                <strong>
                                    Gambar saat ini
                                </strong>

                                <span>
                                    Pilih file baru jika ingin mengganti gambar.
                                </span>

                            </div>

                        </div>

                    @endif

                        <div class="current-image-info">

                            <strong>
                                Gambar saat ini
                            </strong>

                            <span>
                                Pilih file baru jika ingin mengganti gambar.
                            </span>

                        </div>

                    </div>

                @endif

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                @error('gambar')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

                <small class="form-help">
                    Kosongkan jika tidak ingin mengganti gambar.
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                </small>

            </div>


            {{-- DESKRIPSI --}}
            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    placeholder="Masukkan deskripsi sub kategori"
                >{{ old(
                    'deskripsi',
                    $subKategori->deskripsi
                ) }}</textarea>

                @error('deskripsi')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- CATATAN --}}
            <div class="form-note">

                <strong>Catatan:</strong>

                ID sub kategori dan kategori induk tidak dapat diubah.
                Perubahan hanya dilakukan pada nama, gambar, dan deskripsi.

            </div>


            {{-- BUTTON --}}
            <div class="form-actions">

                <a
                    href="{{ route(
                        'kategori.sub-kategori.index',
                        $kategori->id_kategori
                    ) }}"
                    class="cancel-button"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="save-button"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('styles')
<style>

/* =====================================================
   PAGE
===================================================== */

.update-page {

    width: 100%;
    max-width: 1000px;

    margin: 0 auto;

    padding: 125px 30px 60px;

    box-sizing: border-box;
}


/* =====================================================
   HEADER
===================================================== */

.page-header {
    margin-bottom: 28px;
}

.header-left {

    display: flex;

    align-items: flex-start;

    gap: 16px;
}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-button {

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 8px;

    color: #475569;

    text-decoration: none;

    font-size: 20px;

    transition: all 0.2s ease;
}

.back-button:hover {

    color: #2563eb;

    border-color: #2563eb;

    background: #f8fafc;
}


/* =====================================================
   HEADER TEXT
===================================================== */

.page-eyebrow {

    display: block;

    margin-bottom: 5px;

    color: #2563eb;

    font-size: 12px;

    font-weight: 700;

    letter-spacing: 1px;
}

.page-header h1 {

    margin: 0;

    color: #0f172a;

    font-size: 28px;

    font-weight: 700;
}

.page-header p {

    margin: 7px 0 0;

    color: #64748b;

    font-size: 14px;

    line-height: 1.5;
}


/* =====================================================
   FORM CARD
===================================================== */

.form-card {

    width: 100%;

    max-width: 850px;

    margin: 0 auto;

    padding: 28px;

    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 10px;

    box-sizing: border-box;
}


/* =====================================================
   FORM GROUP
===================================================== */

.form-group {

    margin-bottom: 22px;
}

.form-group label {

    display: block;

    margin-bottom: 8px;

    color: #334155;

    font-size: 14px;

    font-weight: 600;
}

.form-group label span {

    color: #dc2626;
}


/* =====================================================
   INPUT
===================================================== */

.form-group input,
.form-group textarea {

    width: 100%;

    box-sizing: border-box;

    padding: 11px 13px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;

    background: white;

    font-family: inherit;

    font-size: 14px;

    color: #334155;

    transition: all 0.15s ease;
}

.form-group input:focus,
.form-group textarea:focus {

    border-color: #2563eb;

    box-shadow: 0 0 0 3px #dbeafe;
}

.form-group input[readonly] {

    background: #f8fafc;

    color: #64748b;

    cursor: not-allowed;
}

.form-group textarea {

    min-height: 120px;

    resize: vertical;
}


/* =====================================================
   CURRENT IMAGE
===================================================== */

.current-image {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-bottom: 10px;

    padding: 10px;

    background: #f8fafc;

    border: 1px solid #e2e8f0;

    border-radius: 8px;
}

.current-image img {

    width: 70px;
    height: 70px;

    object-fit: cover;

    border-radius: 7px;

    border: 1px solid #e2e8f0;
}

.current-image-info {

    display: flex;

    flex-direction: column;

    gap: 3px;
}

.current-image-info strong {

    color: #334155;

    font-size: 13px;
}

.current-image-info span {

    color: #94a3b8;

    font-size: 12px;
}


/* =====================================================
   HELP & ERROR
===================================================== */

.form-help {

    display: block;

    margin-top: 6px;

    color: #94a3b8;

    font-size: 12px;

    line-height: 1.5;
}

.input-error {

    display: block;

    margin-top: 6px;

    color: #dc2626;

    font-size: 12px;
}


/* =====================================================
   NOTE
===================================================== */

.form-note {

    margin-bottom: 24px;

    padding: 13px 15px;

    background: #fffbeb;

    border: 1px solid #fde68a;

    border-radius: 7px;

    color: #92400e;

    font-size: 13px;

    line-height: 1.5;
}


/* =====================================================
   BUTTON
===================================================== */

.form-actions {

    display: flex;

    justify-content: flex-end;

    gap: 10px;

    padding-top: 20px;

    border-top: 1px solid #e2e8f0;
}

.cancel-button,
.save-button {

    padding: 11px 18px;

    border-radius: 7px;

    font-size: 14px;

    font-weight: 600;

    text-decoration: none;

    cursor: pointer;

    transition: all 0.2s ease;
}

.cancel-button {

    background: #f1f5f9;

    border: 1px solid #e2e8f0;

    color: #475569;
}

.cancel-button:hover {

    background: #e2e8f0;
}

.save-button {

    border: 1px solid #2563eb;

    background: #2563eb;

    color: white;
}

.save-button:hover {

    background: #1d4ed8;

    border-color: #1d4ed8;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 768px) {

    .update-page {

        padding: 100px 20px 40px;
    }

    .form-card {

        padding: 20px;
    }

    .form-actions {

        flex-direction: column-reverse;
    }

    .cancel-button,
    .save-button {

        width: 100%;

        text-align: center;

        box-sizing: border-box;
    }

}

</style>
@endpush