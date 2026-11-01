
@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<section class="page-section">

    {{-- HEADER --}}
    <div class="page-top">
        <div class="page-heading">
            <a href="{{ route('dashboard') }}" class="back-link">
                ← Kembali ke Dashboard
            </a>

            <span class="page-eyebrow">MASTER DATA</span>

            <h1>Jenis Aset</h1>

            <p>
                Kelola jenis aset yang digunakan dalam sistem Asset Management.
            </p>
        </div>

        <div class="page-action">
            <a href="{{ route('jenis-aset.create') }}" class="add-button">
                <span>+</span>
                Tambah Jenis Aset
            </a>
        </div>
    </div>

    {{-- INFORMASI DATA --}}
    <div class="data-info">
        <div class="info-left">
            <strong>{{ $jenis->count() }}</strong>
            <span>Jenis aset terdaftar</span>
        </div>

        <div class="info-right">
            Data master jenis aset
        </div>
    </div>

    {{-- TABEL --}}
    <div class="table-card">
        <div class="table-header">
            <div>
                <h3>Daftar Jenis Aset</h3>
                <p>Daftar jenis aset yang tersimpan dalam sistem.</p>
            </div>

            {{-- SEARCH --}}
            <form
                action="{{ route('jenis-aset.index') }}"
                method="GET"
                class="search-form"
            >
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari jenis aset..."
                    maxlength="100"
                >

                <button
                    type="submit"
                    class="search-button"
                    title="Cari"
                >
                    🔍
                </button>
            </form>
        </div>

        {{-- DATA JENIS ASET --}}
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Jenis Aset</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jenis as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <span class="kode-badge">
                                    {{ $item->id_jenis }}
                                </span>
                            </td>

                            <td>
                                <strong class="jenis-name">
                                    {{ $item->nama_jenis }}
                                </strong>
                            </td>

                            <td>
                                <span class="jumlah">
                                    {{ $item->stok }}
                                </span>
                            </td>

                            <td>
                                @if ($item->status_jenis === 'Aktif')
                                    <span class="status-badge active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="deskripsi-text">
                                    {{ $item->deskripsi ?: '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">

                                    {{-- TAMBAH STOK --}}
                                    <button
                                        type="button"
                                        class="stock-button"
                                        title="Tambah Stok"
                                        data-id="{{ $item->id_jenis }}"
                                        data-nama="{{ $item->nama_jenis }}"
                                        data-url="{{ route('jenis-aset.stok', $item->id_jenis) }}"
                                        onclick="bukaModalStok(this)"
                                        @disabled($item->status_jenis !== 'Aktif')
                                    >
                                        +
                                    </button>

                                    {{-- EDIT JENIS ASET --}}
                                    <a
                                        href="{{ route('jenis-aset.edit', $item->id_jenis) }}"
                                        class="edit-button"
                                        title="Ubah Jenis Aset"
                                    >
                                        ✎
                                    </a>

                                    {{-- UBAH STATUS --}}
                                    <form
                                        action="{{ route('jenis-aset.status', $item->id_jenis) }}"
                                        method="POST"
                                        class="status-form"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        @if ($item->status_jenis === 'Aktif')
                                            <button
                                                type="submit"
                                                class="status-button deactivate"
                                                title="Nonaktifkan"
                                            >
                                                Nonaktif
                                            </button>
                                        @else
                                            <button
                                                type="submit"
                                                class="status-button activate"
                                                title="Aktifkan"
                                            >
                                                Aktif
                                            </button>
                                        @endif
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-data">
                                @if (request('search'))
                                    Jenis Aset "{{ request('search') }}"
                                    tidak ditemukan.
                                @else
                                    Belum ada data jenis aset.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- MODAL TAMBAH STOK --}}
<div
    class="modal-overlay"
    id="modalStok"
    aria-hidden="true"
>
    <div
        class="modal-card"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalStokTitle"
    >
        <div class="modal-header">
            <div>
                <h3 id="modalStokTitle">Tambah Stok Aset</h3>
                <p>
                    Masukkan jumlah unit baru yang ingin ditambahkan.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalStok()"
                aria-label="Tutup"
            >
                &times;
            </button>
        </div>

        <form id="formTambahStok" method="POST">
            @csrf

            <div class="modal-body">
                <label for="namaJenisStok">Jenis Aset</label>
                <input
                    type="text"
                    id="namaJenisStok"
                    readonly
                >

                {{-- NAMA FIELD HARUS SAMA DENGAN CONTROLLER --}}
                <label for="tambah_stok">Jumlah Stok Tambahan</label>
                <input
                    type="number"
                    id="tambah_stok"
                    name="tambah_stok"
                    min="1"
                    max="9999"
                    step="1"
                    required
                    placeholder="Contoh: 3"
                >

                <small>
                    Setiap unit tambahan akan dibuat sebagai data Unit Aset
                    tersendiri dengan kode aset masing-masing.
                </small>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="cancel-button"
                    onclick="tutupModalStok()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="confirm-button"
                    id="tombolTambahStok"
                >
                    Tambah Stok
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.page-section {
    width: 100%;
    max-width: 1500px;
    margin: 20px auto 0;
    padding: 32px 40px 60px;
    box-sizing: border-box;
}

/* HEADER */
.page-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
    margin-bottom: 30px;
}

.page-heading {
    flex: 1;
    min-width: 0;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    background: #ffffff;
    border: 1px solid #d8dee8;
    border-radius: 8px;
    color: #315b91;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: all 0.15s ease;
}

.back-link:hover {
    background: #f5f8fc;
    border-color: #315b91;
    color: #244a7c;
}

.page-eyebrow {
    display: block;
    margin-bottom: 6px;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
}

.page-heading h1 {
    margin: 0;
    color: #0f172a;
    font-size: 30px;
    line-height: 1.2;
    font-weight: 700;
}

.page-heading p {
    margin: 8px 0 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
}

/* BUTTON TAMBAH JENIS ASET */
.page-action {
    flex-shrink: 0;
}

.add-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 18px;
    background: #2563eb;
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s;
}

.add-button:hover {
    background: #1d4ed8;
}

.add-button span {
    font-size: 20px;
    line-height: 1;
}

/* INFORMASI DATA */
.data-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 17px 22px;
    margin-bottom: 20px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.info-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-left strong {
    color: #2563eb;
    font-size: 22px;
    font-weight: 700;
}

.info-left span,
.info-right {
    color: #64748b;
    font-size: 13px;
}

/* TABLE CARD */
.table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

/* TABLE HEADER */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px 22px;
    border-bottom: 1px solid #e2e8f0;
}

.table-header h3 {
    margin: 0;
    color: #0f172a;
    font-size: 16px;
    font-weight: 600;
}

.table-header p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
}

/* SEARCH */
.search-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.search-form input {
    width: 240px;
    height: 40px;
    padding: 0 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
    color: #334155;
    font-size: 13px;
    box-sizing: border-box;
}

.search-form input:focus {
    border-color: #2563eb;
}

.search-button {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    background: #2563eb;
    color: #ffffff;
    cursor: pointer;
    font-size: 15px;
    transition: 0.2s;
}

.search-button:hover {
    background: #1d4ed8;
}

/* TABLE */
.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
}

thead {
    background: #11569c;
}

th {
    padding: 14px 18px;
    color: #ffffff;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

td {
    padding: 16px 18px;
    border-top: 1px solid #f1f5f9;
    color: #475569;
    font-size: 14px;
    vertical-align: middle;
}

tbody tr {
    transition: background 0.15s;
}

tbody tr:hover {
    background: #f8fafc;
}

/* KODE DAN NAMA */
.kode-badge {
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.jenis-name {
    color: #07549f;
    font-size: 14px;
    font-weight: 600;
}

/* STOK */
.jumlah {
    color: #334155;
    font-size: 14px;
    font-weight: 600;
}

/* STATUS */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #f1f5f9;
    color: #64748b;
}

.deskripsi-text {
    color: #64748b;
    font-size: 14px;
}

/* AKSI */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-form {
    display: inline;
}

.stock-button,
.edit-button {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s;
    box-sizing: border-box;
}

.stock-button {
    background: #dbeafe;
    color: #07549f;
    font-size: 22px;
    font-weight: 500;
    line-height: 1;
}

.stock-button:hover:not(:disabled) {
    background: #bfdbfe;
    transform: translateY(-1px);
}

.stock-button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.edit-button {
    background: #fff3c4;
    color: #9a6b00;
    font-size: 17px;
}

.edit-button:hover {
    background: #fde68a;
    transform: translateY(-1px);
}

.status-button {
    min-height: 38px;
    padding: 0 11px;
    border: none;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.status-button.deactivate {
    background: #fff3cd;
    color: #946200;
}

.status-button.activate {
    background: #dcfce7;
    color: #166534;
}

.status-button:hover {
    transform: translateY(-1px);
}

/* DATA KOSONG */
.empty-data {
    padding: 45px 20px !important;
    color: #94a3b8;
    text-align: center;
    font-size: 14px;
}

/* ALERT */
.alert-success,
.alert-error {
    width: calc(100% - 88px);
    max-width: 1412px;
    margin: 18px auto 0;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* MODAL TAMBAH STOK */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15, 23, 42, 0.45);
    box-sizing: border-box;
}

.modal-overlay.show {
    display: flex;
}

.modal-card {
    width: 100%;
    max-width: 450px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    padding: 22px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    color: #0f172a;
    font-size: 18px;
}

.modal-header p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.modal-close {
    flex-shrink: 0;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 26px;
    line-height: 1;
    cursor: pointer;
}

.modal-body {
    padding: 22px;
}

.modal-body label {
    display: block;
    margin-bottom: 8px;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
}

.modal-body input {
    width: 100%;
    height: 42px;
    padding: 0 12px;
    margin-bottom: 18px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
    box-sizing: border-box;
    color: #334155;
    font-family: inherit;
    font-size: 14px;
}

.modal-body input:focus {
    border-color: #2563eb;
}

.modal-body input[readonly] {
    background: #f8fafc;
    color: #64748b;
}

.modal-body small {
    display: block;
    margin-top: -8px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 22px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.cancel-button,
.confirm-button {
    padding: 10px 15px;
    border: none;
    border-radius: 7px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.cancel-button {
    background: #e2e8f0;
    color: #334155;
}

.confirm-button {
    background: #2563eb;
    color: #ffffff;
}

.confirm-button:hover {
    background: #1d4ed8;
}

.confirm-button:disabled {
    opacity: 0.6;
    cursor: wait;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .page-section {
        padding: 70px 20px 40px;
    }

    .page-top {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-action {
        width: 100%;
    }

    .add-button {
        width: 100%;
        box-sizing: border-box;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .search-form {
        width: 100%;
    }

    .search-form input {
        flex: 1;
        width: auto;
    }

    .data-info {
        gap: 10px;
    }
}

@media (max-width: 600px) {
    .page-section {
        padding: 65px 15px 35px;
    }

    .data-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .info-right {
        display: none;
    }

    th,
    td {
        padding: 13px 12px;
    }

    .alert-success,
    .alert-error {
        width: calc(100% - 30px);
    }

    .modal-card {
        max-width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
    function bukaModalStok(button) {
        if (button.disabled) {
            return;
        }

        const modal = document.getElementById('modalStok');
        const form = document.getElementById('formTambahStok');
        const jumlahInput = document.getElementById('tambah_stok');
        const tombol = document.getElementById('tombolTambahStok');

        document.getElementById('namaJenisStok').value =
            button.dataset.nama;

        form.action = button.dataset.url;
        jumlahInput.value = '';
        jumlahInput.setCustomValidity('');

        tombol.disabled = false;
        tombol.textContent = 'Tambah Stok';

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        jumlahInput.focus();
    }

    function tutupModalStok() {
        const modal = document.getElementById('modalStok');

        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('modalStok').addEventListener(
        'click',
        function (event) {
            if (event.target === this) {
                tutupModalStok();
            }
        }
    );

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            tutupModalStok();
        }
    });

    document.getElementById('formTambahStok').addEventListener(
        'submit',
        function (event) {
            const jumlahInput = document.getElementById('tambah_stok');
            const jumlah = Number(jumlahInput.value);

            jumlahInput.setCustomValidity('');

            if (
                !Number.isInteger(jumlah) ||
                jumlah < 1 ||
                jumlah > 9999
            ) {
                event.preventDefault();

                jumlahInput.setCustomValidity(
                    'Jumlah stok harus berupa bilangan bulat antara 1 dan 9999.'
                );

                jumlahInput.reportValidity();
                jumlahInput.setCustomValidity('');

                return;
            }

            const tombol = document.getElementById('tombolTambahStok');
            tombol.disabled = true;
            tombol.textContent = 'Memproses...';
        }
    );
</script>
@endpush
