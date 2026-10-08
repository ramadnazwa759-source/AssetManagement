@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert-success">
        ✓ {{ session('success') }}
    </div>
@endif

<section class="page-section">

    {{-- HEADER --}}
    <div class="page-top">
        <div class="page-heading">

            <a href="{{ route('kategori.index') }}" class="back-link">
                ← Kembali ke Kategori Aset
            </a>

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>Sub Kategori Aset</h1>

            <p>
                Kelola sub kategori dari kategori
                <strong>{{ $kategori->nama_kategori }}</strong>.
            </p>

        </div>

        <div class="page-action">
            <a
                href="{{ route(
                    'kategori.sub-kategori.create',
                    $kategori->id_kategori
                ) }}"
                class="add-button"
            >
                <span>+</span>
                Tambah Sub Kategori
            </a>
        </div>
    </div>


    {{-- INFORMASI DATA --}}
    <div class="data-info">

        <div class="info-left">
            <strong>
                {{ $subKategori->count() }}
            </strong>

            <span>
                Sub kategori terdaftar
            </span>
        </div>

        <div class="info-right">
            Kategori:

            <strong>
                {{ $kategori->nama_kategori }}
            </strong>

            <span>
                ({{ $kategori->id_kategori }})
            </span>
        </div>

    </div>


    {{-- TABLE --}}
    <div class="table-card">

        <div class="table-header">

            <div>
                <h3>
                    Daftar Sub Kategori
                </h3>

                <p>
                    Daftar sub kategori yang termasuk dalam
                    kategori {{ $kategori->nama_kategori }}.
                </p>
            </div>


            {{-- SEARCH --}}
            <form
                action="{{ route(
                    'kategori.sub-kategori.index',
                    $kategori->id_kategori
                ) }}"
                method="GET"
                class="search-form"
            >
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari sub kategori..."
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


        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID Sub Kategori</th>
                        <th>Nama Sub Kategori</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse ($subKategori as $item)

                        <tr>

                            <td>
                                <span class="kode-badge">
                                    {{ $item->id_sub_kategori }}
                                </span>
                            </td>

                            <td>
                                <strong class="sub-kategori-name">
                                    {{ $item->nama_sub_kategori }}
                                </strong>
                            </td>

                            <td>
                                @if ($item->gambar)

                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($item->gambar) }}"
                                        alt="Gambar {{ $item->nama_sub_kategori }}"
                                        class="sub-kategori-image"
                                    >

                                @else

                                    <span class="no-image">
                                        Tidak ada gambar
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

                                    <a
                                        href="{{ route(
                                            'kategori.sub-kategori.edit',
                                            [
                                                'id_kategori' => $kategori->id_kategori,
                                                'id' => $item->id_sub_kategori
                                            ]
                                        ) }}"
                                        class="edit-button"
                                        title="Ubah Sub Kategori"
                                    >
                                        ✎
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="empty-data"
                            >
                                @if (request('search'))

                                    Sub kategori
                                    "{{ request('search') }}"
                                    tidak ditemukan.

                                @else

                                    Belum ada sub kategori untuk
                                    kategori ini.

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

.page-heading p strong {
    color: #0f172a;
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

.info-right strong {
    color: #0f172a;
}

.info-right span {
    color: #94a3b8;
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
    width: 250px;
    height: 38px;
    padding: 0 12px;
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
    width: 38px;
    height: 38px;
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
   ID
===================================================== */

.kode-badge {
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}


/* =====================================================
   NAMA SUB KATEGORI
===================================================== */

.sub-kategori-name {
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
   EDIT
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
   ALERT SUCCESS
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

    .data-info {
        gap: 10px;
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
        font-size: 12px;
    }

    th,
    td {
        padding: 13px 12px;
    }
}


/* =====================================================
   GAMBAR SUB KATEGORI
===================================================== */

.sub-kategori-image {
    width: 70px;
    height: 55px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    display: block;
}

.no-image {
    color: #94a3b8;
    font-size: 12px;
}

</style>

@endpush