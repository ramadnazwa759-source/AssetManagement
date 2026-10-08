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
                ← Kembali ke Beranda
            </a>

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>Kategori Aset</h1>

            <p>
                Kelola kategori aset yang digunakan dalam sistem Asset Management.
            </p>

        </div>

        <div class="page-action">

            <a
                href="{{ route('kategori.create') }}"
                class="add-button"
            >
                <span>+</span>
                Tambah Kategori Aset
            </a>

        </div>

    </div>


    {{-- =====================================================
        INFORMASI DATA
    ===================================================== --}}

    <div class="data-info">

        <div class="info-left">

            <strong>
                {{ $kategori->count() }}
            </strong>

            <span>
                Kategori aset terdaftar
            </span>

        </div>

        <div class="info-right">
            Data master kategori aset
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
                    Daftar Kategori Aset
                </h3>

                <p>
                    Daftar kategori aset yang tersimpan dalam sistem.
                </p>

            </div>


            {{-- SEARCH --}}

            <form
                action="{{ route('kategori.index') }}"
                method="GET"
                class="search-form"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kategori..."
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

                        <th>ID Kategori</th>

                        <th>Nama Kategori</th>

                        <th>Deskripsi</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($kategori as $item)

                        <tr>

                            {{-- ID KATEGORI --}}

                            <td>

                                <span class="kode-badge">
                                    {{ $item->id_kategori }}
                                </span>

                            </td>


                            {{-- NAMA KATEGORI --}}

                            <td>

                                <strong class="kategori-name">
                                    {{ $item->nama_kategori }}
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

                                    {{-- SUB KATEGORI --}}

                                    <a
                                        href="{{ route(
                                            'kategori.sub-kategori.index',
                                            $item->id_kategori
                                        ) }}"
                                        class="sub-button"
                                        title="Kelola Sub Kategori"
                                    >
                                        +
                                    </a>


                                    {{-- EDIT KATEGORI --}}

                                    <a
                                        href="{{ route(
                                            'kategori.update.form',
                                            $item->id_kategori
                                        ) }}"
                                        class="edit-button"
                                        title="Ubah Kategori"
                                    >
                                        ✎
                                    </a>

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

                                    Kategori
                                    "{{ request('search') }}"
                                    tidak ditemukan.

                                @else

                                    Belum ada data kategori aset.

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</section>

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

    margin: 0 auto;

    padding: 85px 44px 45px;

    box-sizing: border-box;

}


/* =====================================================
   HEADER
===================================================== */

.page-top {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    gap: 30px;

    margin-bottom: 28px;

}


.page-heading {

    flex: 1;

    min-width: 0;

}


.back-link {

    display: inline-block;

    margin-bottom: 14px;

    color: #64748b;

    text-decoration: none;

    font-size: 13px;

    font-weight: 500;

    transition: 0.2s;

}


.back-link:hover {

    color: #2563eb;

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
   ID KATEGORI
===================================================== */

.kode-badge {

    color: #64748b;

    font-size: 13px;

    font-weight: 600;

    white-space: nowrap;

}


/* =====================================================
   NAMA KATEGORI
===================================================== */

.kategori-name {

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
   TOMBOL SUB KATEGORI (+)
===================================================== */

.sub-button {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #dbeafe;

    color: #07549f;

    border-radius: 7px;

    text-decoration: none;

    font-size: 23px;

    font-weight: 500;

    line-height: 1;

    transition: 0.2s;

}


.sub-button:hover {

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