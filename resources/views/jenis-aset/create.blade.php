@extends('layouts.app')

@section('content')

<div class="create-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div class="header-left">

            <a href="/jenis-aset" class="back-button">
                ←
            </a>

            <div>
                <span class="page-eyebrow">MASTER DATA</span>

                <h1>Tambah Jenis Aset</h1>

                <p>
                    Tambahkan jenis aset baru beserta stok awal yang tersedia.
                </p>
            </div>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="form-card">

        <div class="form-card-header">

            <div>
                <span class="section-label">
                    INFORMASI JENIS ASET
                </span>

                <h2>Data Jenis Aset</h2>

                <p>
                    Lengkapi informasi jenis aset yang akan ditambahkan.
                </p>
            </div>

        </div>


        <form action="#" method="POST" id="formJenisAset">

            {{-- KODE --}}
            <div class="form-group">

                <label for="kode">
                    Kode Jenis Aset
                    <span>*</span>
                </label>

                <input
                    type="text"
                    id="kode"
                    name="kode"
                    placeholder="Contoh: JA-001"
                    required
                >

                <small>
                    Gunakan kode yang unik untuk jenis aset.
                </small>

            </div>


            {{-- NAMA --}}
            <div class="form-group">

                <label for="nama">
                    Nama Jenis Aset
                    <span>*</span>
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Contoh: Tenda"
                    required
                >

            </div>


            {{-- JUMLAH STOK --}}
            <div class="form-group">

                <label for="jumlah">
                    Jumlah Stok
                    <span>*</span>
                </label>

                <input
                    type="number"
                    id="jumlah"
                    name="jumlah"
                    placeholder="Masukkan jumlah stok"
                    min="1"
                    required
                >

                <small>
                    Jumlah stok awal yang tersedia untuk jenis aset ini.
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
                ></textarea>

            </div>


            {{-- STATUS --}}
            <div class="form-group">

                <label>
                    Status
                </label>

                <div class="status-option">

                    <div class="status-radio">
                        <input
                            type="radio"
                            id="statusAktif"
                            name="status"
                            value="aktif"
                            checked
                        >

                        <label for="statusAktif">
                            Aktif
                        </label>
                    </div>

                    <span class="status-info">
                        Jenis aset dapat digunakan dalam pengelolaan aset.
                    </span>

                </div>

            </div>


            {{-- CATATAN --}}
            <div class="form-note">

                <div class="note-icon">
                    i
                </div>

                <div>
                    <strong>Informasi stok</strong>

                    <p>
                        Jumlah stok yang ditambahkan akan menjadi jumlah awal
                        aset untuk jenis aset ini.
                    </p>
                </div>

            </div>


            {{-- ACTION --}}
            <div class="form-actions">

                <a href="/jenis-aset" class="cancel-button">
                    Batal
                </a>

                <button type="submit" class="save-button">
                    Simpan Jenis Aset
                </button>

            </div>

        </form>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.create-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 32px;
}


/* =========================================================
   HEADER
========================================================= */

.page-header {
    margin-bottom: 24px;
}

.header-left {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.back-button {
    width: 38px;
    height: 38px;

    border-radius: 10px;

    border: 1px solid #dfe5eb;

    background: #ffffff;

    color: #315b7d;

    text-decoration: none;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 20px;

    transition: 0.2s;
}

.back-button:hover {
    background: #f4f7fa;
}

.page-eyebrow {
    display: block;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: 1.2px;

    color: #6c879c;

    margin-bottom: 5px;
}

.page-header h1 {
    margin: 0;

    font-size: 28px;

    color: #183b56;
}

.page-header p {
    margin: 6px 0 0;

    color: #748797;

    font-size: 14px;
}


/* =========================================================
   FORM CARD
========================================================= */

.form-card {
    background: #ffffff;

    border: 1px solid #e3e8ed;

    border-radius: 14px;

    overflow: hidden;
}

.form-card-header {
    padding: 22px 24px;

    border-bottom: 1px solid #edf0f3;
}

.section-label {
    display: block;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1px;

    color: #6c879c;

    margin-bottom: 5px;
}

.form-card-header h2 {
    margin: 0;

    font-size: 19px;

    color: #183b56;
}

.form-card-header p {
    margin: 5px 0 0;

    font-size: 13px;

    color: #7b8b97;
}


/* =========================================================
   FORM
========================================================= */

#formJenisAset {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group > label {
    display: block;

    margin-bottom: 7px;

    font-size: 13px;

    font-weight: 600;

    color: #334f62;
}

.form-group > label span {
    color: #c65353;
}


/* INPUT */

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;

    box-sizing: border-box;

    border: 1px solid #d9e0e6;

    border-radius: 8px;

    padding: 10px 12px;

    background: #ffffff;

    color: #334f62;

    font-family: inherit;

    font-size: 13px;

    outline: none;

    transition: 0.2s;
}

.form-group input[type="text"],
.form-group input[type="number"] {
    height: 42px;
}

.form-group textarea {
    resize: vertical;

    min-height: 110px;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group textarea:focus {
    border-color: #4f83a8;

    box-shadow: 0 0 0 3px rgba(79, 131, 168, 0.10);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #a2adb5;
}


/* HELP TEXT */

.form-group small {
    display: block;

    margin-top: 5px;

    font-size: 11px;

    color: #8a98a2;
}


/* =========================================================
   STATUS
========================================================= */

.status-option {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 14px;

    background: #f7faf8;

    border: 1px solid #dcece2;

    border-radius: 9px;
}

.status-radio {
    display: flex;

    align-items: center;

    gap: 8px;
}

.status-radio input {
    width: 15px;
    height: 15px;

    accent-color: #27804c;
}

.status-radio label {
    margin: 0;

    font-size: 13px;

    font-weight: 600;

    color: #27804c;

    cursor: pointer;
}

.status-info {
    font-size: 11px;

    color: #74877b;
}


/* =========================================================
   INFORMATION NOTE
========================================================= */

.form-note {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 13px 14px;

    margin-top: 5px;

    background: #f5f8fa;

    border: 1px solid #e1e8ed;

    border-radius: 9px;
}

.note-icon {
    width: 20px;
    height: 20px;

    flex-shrink: 0;

    border-radius: 50%;

    background: #dce8f0;

    color: #315b7d;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 12px;

    font-weight: 700;
}

.form-note strong {
    display: block;

    margin-bottom: 2px;

    color: #3d596c;

    font-size: 12px;
}

.form-note p {
    margin: 0;

    color: #788995;

    font-size: 11px;

    line-height: 1.5;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.form-actions {
    display: flex;

    justify-content: flex-end;

    align-items: center;

    gap: 8px;

    padding-top: 22px;

    margin-top: 22px;

    border-top: 1px solid #edf0f3;
}

.cancel-button,
.save-button {
    min-height: 40px;

    padding: 9px 16px;

    border-radius: 8px;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;

    cursor: pointer;

    font-family: inherit;
}

.cancel-button {
    display: flex;

    align-items: center;
    justify-content: center;

    background: #f1f3f5;

    color: #667680;

    border: 1px solid #e2e6e9;
}

.cancel-button:hover {
    background: #e7eaed;
}

.save-button {
    border: none;

    background: #0d4f8b;

    color: #ffffff;

    transition: 0.2s;
}

.save-button:hover {
    background: #0a4275;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .create-page {
        padding: 20px 15px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .status-option {
        align-items: flex-start;

        flex-direction: column;

        gap: 7px;
    }

}


@media (max-width: 480px) {

    .header-left {
        gap: 10px;
    }

    .form-card-header,
    #formJenisAset {
        padding-left: 18px;
        padding-right: 18px;
    }

    .form-actions {
        flex-direction: column-reverse;

        align-items: stretch;
    }

    .cancel-button,
    .save-button {
        width: 100%;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.getElementById('formJenisAset').addEventListener('submit', function(event) {

    event.preventDefault();

    alert('Data jenis aset siap disimpan ke database.');

});

</script>

@endpush

@endsection