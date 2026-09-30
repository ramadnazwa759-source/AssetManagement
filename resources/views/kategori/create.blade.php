@extends('layouts.app')

@section('content')

<div class="create-page">

    {{-- HEADER --}}
    <div class="page-header">

        {{-- TOMBOL KEMBALI KE KATEGORI --}}
        <a href="{{ route('kategori.index') }}" class="back-home-button">
          Kembali ke beranda
        </a>

        <div class="header-content">

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>
                Tambah Kategori Aset
            </h1>

            <p>
                Tambahkan kategori aset baru ke dalam sistem.
            </p>

        </div>

    </div>


    {{-- FORM --}}
    <div class="form-card">

        <form
            action="{{ route('kategori.store') }}"
            method="POST"
        >

            @csrf


            {{-- ID KATEGORI --}}
            <div class="form-group">

                <label for="id_kategori">
                    ID Kategori
                </label>

                <input
                    type="text"
                    id="id_kategori"
                    name="id_kategori"
                    value="{{ $idKategoriBaru ?? old('id_kategori') }}"
                    readonly
                >

            </div>


            {{-- NAMA KATEGORI --}}
            <div class="form-group">

                <label for="nama_kategori">
                    Nama Kategori <span>*</span>
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori') }}"
                    placeholder="Masukkan nama kategori"
                    required
                >

                @error('nama_kategori')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- DESKRIPSI --}}
            <div class="form-group">

                <label for="Deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="Deskripsi"
                    name="Deskripsi"
                    rows="5"
                    placeholder="Masukkan deskripsi kategori..."
                >{{ old('Deskripsi') }}</textarea>

                @error('Deskripsi')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- CATATAN --}}
            <div class="form-note">

                <strong>Catatan:</strong>

                Pastikan nama kategori yang dimasukkan sudah sesuai.
                Setelah kategori dibuat, Sub Kategori dapat ditambahkan
                melalui tombol <strong>+</strong> pada daftar kategori.

            </div>


            {{-- BUTTON --}}
            <div class="form-actions">

                <a
                    href="{{ route('kategori.index') }}"
                    class="cancel-button"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="save-button"
                >
                    Simpan Kategori
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('styles')
<style>

/* =====================================================
   HALAMAN
===================================================== */

.create-page {
    width: 100%;
    max-width: 1000px;

    margin: 0 auto;

    padding: 105px 30px 60px;

    box-sizing: border-box;
}


/* =====================================================
   HEADER
===================================================== */

.page-header {
    margin-bottom: 28px;
}




/* =====================================================
   TOMBOL KEMBALI KE BERANDA
===================================================== */

.back-home-button {
    display: inline-flex;

    align-items: center;
    gap: 8px;

    margin-bottom: 18px;

    padding: 10px 16px;

    background: #11569c;
    color: #ffffff;

    border: 1px solid #11569c;

    border-radius: 8px;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

    transition: 0.2s;

    box-shadow: 0 2px 5px rgba(17, 86, 156, 0.15);
}

.back-home-button:hover {
    background: #0d477f;
    border-color: #0d477f;
    color: #ffffff;

    transform: translateY(-1px);

    box-shadow: 0 4px 8px rgba(17, 86, 156, 0.20);
}


/* =====================================================
   JUDUL
===================================================== */

.header-content {
    padding-left: 2px;
}

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

    line-height: 1.3;

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

    background: white;

    padding: 28px;

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
   INPUT & TEXTAREA
===================================================== */

.form-group input,
.form-group textarea {
    width: 100%;

    box-sizing: border-box;

    padding: 12px 13px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;

    font-family: inherit;

    font-size: 14px;

    color: #334155;

    background: #ffffff;

    transition: 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #2563eb;

    box-shadow: 0 0 0 3px #dbeafe;
}


/* =====================================================
   ID READONLY
===================================================== */

.form-group input[readonly] {
    background: #f8fafc;

    color: #64748b;

    cursor: not-allowed;
}


/* =====================================================
   TEXTAREA
===================================================== */

.form-group textarea {
    resize: vertical;

    min-height: 120px;
}


/* =====================================================
   ERROR
===================================================== */

.input-error {
    display: block;

    margin-top: 6px;

    color: #dc2626;

    font-size: 12px;
}


/* =====================================================
   CATATAN
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
   BUTTON FORM
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

    transition: 0.2s;
}


/* BATAL */

.cancel-button {
    background: #f1f5f9;

    color: #475569;
}

.cancel-button:hover {
    background: #e2e8f0;
}


/* SIMPAN */

.save-button {
    border: none;

    background: #2563eb;

    color: white;
}

.save-button:hover {
    background: #1d4ed8;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 768px) {

    .create-page {
        padding: 90px 20px 40px;
    }

    .page-header h1 {
        font-size: 24px;
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