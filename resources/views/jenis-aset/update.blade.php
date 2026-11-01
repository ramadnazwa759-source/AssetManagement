@extends('layouts.app')

@section('content')

<div class="create-page">

{{-- HEADER --}}
<div class="page-header">

    <div class="header-left">

        <a href="{{ route('jenis-aset.index') }}" class="back-button">
            ←
        </a>

        <div>

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>
                Update Jenis Aset
            </h1>

            <p>
                Perbarui informasi jenis aset yang telah terdaftar.
            </p>

        </div>

    </div>

</div>


{{-- FORM --}}
<div class="form-card">

    <form
        action="{{ route('jenis-aset.update', $jenis->id_jenis) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        {{-- KODE JENIS ASET --}}
        <div class="form-group">

            <label for="id_jenis">
                Kode Jenis Aset
            </label>

            <input
                type="text"
                id="id_jenis"
                value="{{ $jenis->id_jenis }}"
                readonly
            >

            <small>
                Kode jenis aset merupakan identitas tetap dan tidak dapat diubah.
            </small>

        </div>


        {{-- SUB KATEGORI --}}
        <div class="form-group">

            <label for="id_sub_kategori_aset">
                Sub Kategori Aset <span>*</span>
            </label>

            <select
                id="id_sub_kategori_aset"
                name="id_sub_kategori_aset"
                required
            >

                <option value="">
                    Pilih Sub Kategori
                </option>

                @foreach ($subKategori as $item)

                    <option
                        value="{{ $item->id_sub_kategori }}"
                        {{ old(
                            'id_sub_kategori_aset',
                            $jenis->id_sub_kategori_aset
                        ) == $item->id_sub_kategori ? 'selected' : '' }}
                    >
                        {{ $item->nama_sub_kategori }}
                    </option>

                @endforeach

            </select>

            @error('id_sub_kategori_aset')
                <small class="input-error">
                    {{ $message }}
                </small>
            @enderror

            @if (!$errors->has('id_sub_kategori_aset'))
                <small>
                    Pilih sub kategori yang sesuai dengan jenis aset.
                </small>
            @endif

        </div>


        {{-- NAMA JENIS ASET --}}
        <div class="form-group">

            <label for="nama_jenis">
                Nama Jenis Aset <span>*</span>
            </label>

            <input
                type="text"
                id="nama_jenis"
                name="nama_jenis"
                value="{{ old('nama_jenis', $jenis->nama_jenis) }}"
                placeholder="Masukkan nama jenis aset"
                maxlength="100"
                required
            >

            @error('nama_jenis')
                <small class="input-error">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- STOK --}}
        <div class="form-group">

            <label for="stok">
                Stok
            </label>

            <input
                type="number"
                id="stok"
                value="{{ $jenis->stok }}"
                readonly
            >

            <small>
                Stok tidak diubah melalui halaman ini. Gunakan fitur
                <strong>+ Stok</strong> pada daftar Jenis Aset untuk menambah stok.
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
                placeholder="Masukkan deskripsi jenis aset..."
            >{{ old('deskripsi', $jenis->deskripsi) }}</textarea>

            @error('deskripsi')
                <small class="input-error">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- STATUS --}}
        <div class="form-group">

            <label for="status_jenis">
                Status
            </label>

            <input
                type="text"
                id="status_jenis"
                value="{{ $jenis->status_jenis }}"
                readonly
            >

            <small>
                Status diubah melalui tombol Aktifkan atau Nonaktifkan
                pada daftar Jenis Aset.
            </small>

        </div>


        {{-- CATATAN --}}
        <div class="form-note">

            <strong>Catatan:</strong>

            Kode Jenis Aset merupakan identitas tetap sehingga tidak dapat
            diubah. Stok dan status juga dikelola melalui fitur masing-masing.
            Perubahan pada halaman ini hanya berlaku untuk Sub Kategori,
            Nama Jenis Aset, dan Deskripsi.

        </div>


        {{-- BUTTON --}}
        <div class="form-actions">

            <a
                href="{{ route('jenis-aset.index') }}"
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
```

</div>

@endsection

@push('styles')

<style>

/* =====================================================
   PAGE
===================================================== */

.create-page {

    width: 100%;

    max-width: 1000px;

    margin: 0 auto;

    padding: 65px 30px 60px;

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

.back-button {

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 8px;

    color: #475569;

    text-decoration: none;

    font-size: 20px;

    transition: 0.2s;
}

.back-button:hover {

    color: #2563eb;

    border-color: #2563eb;
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
   INPUT
===================================================== */

.form-group input,
.form-group select,
.form-group textarea {

    width: 100%;

    box-sizing: border-box;

    padding: 11px 13px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;

    font-family: inherit;

    font-size: 14px;

    color: #334155;

    background: #ffffff;
}

.form-group input,
.form-group select {

    height: 42px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {

    border-color: #2563eb;

    box-shadow: 0 0 0 3px #dbeafe;
}

.form-group input::placeholder,
.form-group textarea::placeholder {

    color: #94a3b8;
}

.form-group input[readonly] {

    background: #f8fafc;

    color: #64748b;

    cursor: not-allowed;
}

.form-group textarea {

    resize: vertical;

    min-height: 120px;
}


/* =====================================================
   HELP TEXT
===================================================== */

.form-group small {

    display: block;

    margin-top: 6px;

    color: #64748b;

    font-size: 12px;

    line-height: 1.5;
}


/* =====================================================
   ERROR
===================================================== */

.input-error {

    display: block;

    margin-top: 6px;

    color: #dc2626 !important;

    font-size: 12px !important;
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

.form-note strong {

    color: #78350f;
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

    transition: 0.2s;
}

.cancel-button {

    background: #f1f5f9;

    color: #475569;

    border: 1px solid #e2e8f0;
}

.cancel-button:hover {

    background: #e2e8f0;
}

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
