@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert-success">
        ✓ {{ session('success') }}
    </div>
@endif

<section class="page-section">

    {{-- =====================================================
        HEADER
    ===================================================== --}}

    <div class="page-top">

        <div class="page-heading">

            <a href="{{ route('dashboard') }}" class="back-link">
                ← Kembali ke Dashboard
            </a>

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>
                Lokasi Aset
            </h1>

            <p>
                Kelola lokasi tetap yang menjadi identitas aset
            </p>

        </div>


        <div class="page-action">

            <a
                href="{{ route('lokasi.create') }}"
                class="add-button"
            >
                <span>+</span>
                Tambah Lokasi
            </a>

        </div>

    </div>


    {{-- =====================================================
        INFORMASI DATA
    ===================================================== --}}

    <div class="data-info">

        <div class="info-left">

            <strong>
                {{ $lokasi->count() }}
            </strong>

            <span>
                Lokasi aset terdaftar
            </span>

        </div>


        <div class="info-right">
            Data master lokasi aset
        </div>

    </div>


    {{-- =====================================================
        TABLE
    ===================================================== --}}

    <div class="table-card">

        {{-- TABLE HEADER + SEARCH --}}

        <div class="table-header">

            <div>

                <h3>
                    Daftar Lokasi Aset
                </h3>

                <p>
                    Kelola lokasi tetap yang menjadi identitas aset.
                </p>

            </div>


            {{-- SEARCH --}}

            <form
                action="{{ route('lokasi.index') }}"
                method="GET"
                class="search-form"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama lokasi..."
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


        {{-- =====================================================
            TABLE DATA
        ===================================================== --}}

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>ID Lokasi</th>
                        <th>Nama Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($lokasi as $item)

                        <tr>

                            {{-- ID LOKASI --}}

                            <td>

                                <span class="kode-badge">
                                    {{ $item->id_lokasi }}
                                </span>

                            </td>


                            {{-- NAMA LOKASI --}}

                            <td>

                                <strong class="lokasi-name">
                                    {{ $item->nama_lokasi }}
                                </strong>

                            </td>


                            {{-- DESKRIPSI --}}

                            <td>

                                <span class="deskripsi-text">
                                    {{ $item->deskripsi ?: '-' }}
                                </span>

                            </td>


                            {{-- AKSI --}}

                            <td>

                                <div class="action-buttons">

                                    {{-- DETAIL --}}

                                    <button
                                        type="button"
                                        class="detail-button"
                                        onclick="openDetail(
                                            '{{ $item->id_lokasi }}',
                                            '{{ addslashes($item->nama_lokasi) }}',
                                            '{{ addslashes($item->deskripsi ?: '-') }}'
                                        )"
                                        title="Detail Lokasi"
                                    >
                                        ⓘ
                                    </button>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'lokasi.edit',
                                            $item->id_lokasi
                                        ) }}"
                                        class="edit-button"
                                        title="Ubah Lokasi"
                                    >
                                        ✎
                                    </a>


                                    {{-- HAPUS --}}

                                    <form
                                        action="{{ route(
                                            'lokasi.destroy',
                                            $item->id_lokasi
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini? Data aset yang menggunakan lokasi ini tetap dipertahankan.')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-button"
                                            title="Hapus Lokasi"
                                        >
                                            🗑
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="empty-data"
                            >

                                @if(request('search'))

                                    Lokasi
                                    "{{ request('search') }}"
                                    tidak ditemukan.

                                @else

                                    Belum ada data lokasi aset.

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</section>


{{-- =====================================================
    MODAL DETAIL
===================================================== --}}

<div id="detailModal" class="modal">

    <div class="modal-content">

        <span
            class="close-modal"
            onclick="closeDetail()"
        >
            &times;
        </span>


        <h2>
            Detail Lokasi Aset
        </h2>


        {{-- ID --}}

        <div class="detail-item">

            <label>
                ID Lokasi
            </label>

            <div id="detailId"></div>

        </div>


        {{-- NAMA --}}

        <div class="detail-item">

            <label>
                Nama Lokasi
            </label>

            <div id="detailNama"></div>

        </div>


        {{-- DESKRIPSI --}}

        <div class="detail-item">

            <label>
                Deskripsi
            </label>

            <div id="detailDeskripsi"></div>

        </div>


        <button
            type="button"
            class="btn-tutup"
            onclick="closeDetail()"
        >
            Tutup
        </button>

    </div>

</div>


<script>

    function openDetail(id, nama, deskripsi) {

        document.getElementById('detailId').innerText = id;

        document.getElementById('detailNama').innerText = nama;

        document.getElementById('detailDeskripsi').innerText = deskripsi;

        document.getElementById('detailModal').style.display = 'flex';

    }


    function closeDetail() {

        document.getElementById('detailModal').style.display = 'none';

    }


    window.onclick = function(event) {

        const modal = document.getElementById('detailModal');

        if (event.target === modal) {

            modal.style.display = 'none';

        }

    }

</script>

@endsection


{{-- =====================================================
    CSS
===================================================== --}}

@push('styles')

<style>

/* =====================================================
   PAGE
===================================================== */

.page-section {

    width: 100%;

    max-width: 1500px;

    margin: 20px auto 0;

    padding: 32px 40px 60px;

    box-sizing: border-box;

}


/* =====================================================
   HEADER
===================================================== */

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


/* =====================================================
   BUTTON TAMBAH
===================================================== */

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


/* =====================================================
   INFORMASI DATA
===================================================== */

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


.info-left span {

    color: #64748b;

    font-size: 13px;

}


.info-right {

    color: #64748b;

    font-size: 13px;

}


/* =====================================================
   TABLE CARD
===================================================== */

.table-card {

    background: #ffffff;

    border: 1px solid #e2e8f0;

    border-radius: 10px;

    overflow: hidden;

}


/* =====================================================
   TABLE HEADER
===================================================== */

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


/* =====================================================
   SEARCH
===================================================== */

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


/* =====================================================
   TABLE
===================================================== */

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


/* =====================================================
   ID LOKASI
===================================================== */

.kode-badge {

    color: #64748b;

    font-size: 13px;

    font-weight: 600;

    white-space: nowrap;

}


/* =====================================================
   NAMA LOKASI
===================================================== */

.lokasi-name {

    color: #07549f;

    font-size: 14px;

    font-weight: 600;

}


/* =====================================================
   DESKRIPSI
===================================================== */

.deskripsi-text {

    color: #64748b;

    font-size: 14px;

}


/* =====================================================
   AKSI
===================================================== */

.action-buttons {

    display: flex;

    align-items: center;

    gap: 8px;

}


/* =====================================================
   TOMBOL DETAIL
===================================================== */

.detail-button {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #dbeafe;

    color: #07549f;

    border: none;

    border-radius: 7px;

    cursor: pointer;

    font-size: 17px;

    transition: 0.2s;

}


.detail-button:hover {

    background: #bfdbfe;

    transform: translateY(-1px);

}


/* =====================================================
   TOMBOL EDIT
===================================================== */

.edit-button {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #fff3c4;

    color: #9a6b00;

    border-radius: 7px;

    text-decoration: none;

    font-size: 17px;

    transition: 0.2s;

}


.edit-button:hover {

    background: #fde68a;

    transform: translateY(-1px);

}


/* =====================================================
   TOMBOL DELETE
===================================================== */

.delete-button {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #fee2e2;

    color: #b91c1c;

    border: none;

    border-radius: 7px;

    cursor: pointer;

    font-size: 15px;

    transition: 0.2s;

}


.delete-button:hover {

    background: #fecaca;

    transform: translateY(-1px);

}


/* =====================================================
   EMPTY DATA
===================================================== */

.empty-data {

    padding: 45px 20px !important;

    color: #94a3b8;

    text-align: center;

    font-size: 14px;

}


/* =====================================================
   ALERT
===================================================== */

.alert-success {

    width: calc(100% - 88px);

    max-width: 1412px;

    margin: 18px auto 0;

    padding: 12px 18px;

    background: #f0fdf4;

    border: 1px solid #bbf7d0;

    border-radius: 8px;

    color: #166534;

    font-size: 14px;

    box-sizing: border-box;

}


/* =====================================================
   MODAL
===================================================== */

.modal {

    display: none;

    position: fixed;

    z-index: 999;

    left: 0;

    top: 0;

    width: 100%;

    height: 100%;

    background: rgba(0, 0, 0, 0.45);

    align-items: center;

    justify-content: center;

}


.modal-content {

    background: white;

    width: 90%;

    max-width: 500px;

    border-radius: 12px;

    padding: 25px;

    position: relative;

}


.modal-content h2 {

    margin-top: 0;

    margin-bottom: 20px;

    color: #1e3a8a;

}


.detail-item {

    margin-bottom: 15px;

}


.detail-item label {

    display: block;

    font-size: 13px;

    color: #64748b;

    margin-bottom: 5px;

}


.detail-item div {

    padding: 10px 12px;

    background: #f8fafc;

    border-radius: 7px;

    color: #334155;

}


.close-modal {

    position: absolute;

    right: 18px;

    top: 12px;

    font-size: 25px;

    cursor: pointer;

    color: #64748b;

}


.close-modal:hover {

    color: #1e293b;

}


.btn-tutup {

    margin-top: 10px;

    background: #64748b;

    color: white;

    border: none;

    padding: 9px 15px;

    border-radius: 7px;

    cursor: pointer;

}


.btn-tutup:hover {

    background: #475569;

}


/* =====================================================
   RESPONSIVE
===================================================== */

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

}

</style>

@endpush