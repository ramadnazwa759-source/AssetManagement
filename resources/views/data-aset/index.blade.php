
@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-error">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
            <h1>Data Aset</h1>

            <p>
                Kelola data unit aset yang terdaftar dalam sistem
                Asset Management.
            </p>
        </div>
    </div>

    {{-- INFORMASI DATA --}}
    <div class="data-info">
        <div class="info-left">
            <strong>{{ $aset->count() }}</strong>
            <span>Unit aset ditampilkan</span>
        </div>

        <div class="info-right">
            Data unit aset
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="table-card">

        <div class="table-header">
            <div class="table-heading">
                <h3>Daftar Data Aset</h3>
                <p>
                    Daftar unit aset beserta jenis, lokasi, kondisi,
                    status, dan waktu penambahan stoknya.
                </p>
            </div>

            {{-- PENCARIAN --}}
            <form
                action="{{ route('aset.index') }}"
                method="GET"
                class="search-form"
            >
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kode atau nama aset..."
                    maxlength="100"
                    aria-label="Cari data aset"
                >

                {{-- Pertahankan filter saat melakukan pencarian --}}
                @foreach (['id_jenis', 'id_lokasi', 'status_aset'] as $filter)
                    @if (request($filter))
                        <input
                            type="hidden"
                            name="{{ $filter }}"
                            value="{{ request($filter) }}"
                        >
                    @endif
                @endforeach

                <button type="submit" class="search-button">
                    Cari
                </button>
            </form>
        </div>

        {{-- FILTER UTAMA --}}
        <form
            action="{{ route('aset.index') }}"
            method="GET"
            class="filter-form"
        >
            <input
                type="hidden"
                name="search"
                value="{{ request('search') }}"
            >

            {{-- JENIS ASET --}}
            <div class="filter-field">
                <label for="id_jenis">Jenis Aset</label>

                <select name="id_jenis" id="id_jenis">
                    <option value="">Semua Jenis</option>

                    @isset($jenisAset)
                        @foreach ($jenisAset as $jenis)
                            <option
                                value="{{ $jenis->id_jenis }}"
                                @selected(request('id_jenis') == $jenis->id_jenis)
                            >
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

            {{-- LOKASI --}}
            <div class="filter-field">
                <label for="id_lokasi">Lokasi</label>

                <select name="id_lokasi" id="id_lokasi">
                    <option value="">Semua Lokasi</option>

                    @isset($lokasi)
                        @foreach ($lokasi as $itemLokasi)
                            <option
                                value="{{ $itemLokasi->id_lokasi }}"
                                @selected(request('id_lokasi') == $itemLokasi->id_lokasi)
                            >
                                {{ $itemLokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

            {{-- STATUS --}}
            <div class="filter-field">
                <label for="status_aset">Status</label>

                <select name="status_aset" id="status_aset">
                    <option value="">Semua Status</option>

                    @foreach ([
                        'Tersedia',
                        'Dipinjam',
                        'Perlu Perbaikan',
                        'Nonaktif'
                    ] as $status)
                        <option
                            value="{{ $status }}"
                            @selected(request('status_aset') === $status)
                        >
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TOMBOL FILTER --}}
            <div class="filter-actions">
                <button type="submit" class="filter-button">
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('aset.index') }}"
                    class="reset-button"
                >
                    Reset
                </a>
            </div>
        </form>

        {{-- TABEL --}}
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Aset</th>
                        <th>Nama Aset</th>
                        <th>Jenis Aset</th>
                        <th>Lokasi</th>
                        <th>Tanggal Beli</th>
                        <th>Waktu Penambahan Stok</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($aset as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <span class="kode-badge">
                                    {{ $item->kode_aset }}
                                </span>
                            </td>

                            <td>
                                <strong class="asset-name">
                                    {{ $item->nama_aset }}
                                </strong>
                            </td>

                            <td>
                                {{ $item->jenis->nama_jenis ?? '-' }}
                            </td>

                            <td>
                                {{ $item->lokasi->nama_lokasi ?? '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_beli
                                    ? $item->tanggal_beli->format('d-m-Y')
                                    : '-' }}
                            </td>

                            {{-- WAKTU PENAMBAHAN STOK --}}
                            <td class="stock-date">
                                {{ $item->created_at
                                    ? $item->created_at->format('d-m-Y H:i')
                                    : '-' }}
                            </td>

                            <td>
                                @if ($item->kondisi_aset === 'Baik')
                                    <span class="condition-badge good">
                                        Baik
                                    </span>
                                @elseif ($item->kondisi_aset === 'Rusak Ringan')
                                    <span class="condition-badge minor">
                                        Rusak Ringan
                                    </span>
                                @else
                                    <span class="condition-badge damaged">
                                        Rusak Berat
                                    </span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusClass = match ($item->status_aset) {
                                        'Tersedia' => 'available',
                                        'Dipinjam' => 'borrowed',
                                        'Perlu Perbaikan' => 'repair',
                                        'Nonaktif' => 'inactive',
                                        default => 'inactive',
                                    };
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    {{ $item->status_aset }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a
                                        href="{{ route('aset.show', $item->kode_aset) }}"
                                        class="detail-button"
                                        title="Detail Aset"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('aset.edit', $item->kode_aset) }}"
                                        class="edit-button"
                                        title="Ubah Data Aset"
                                        aria-label="Ubah {{ $item->nama_aset }}"
                                    >
                                        ✎
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty-data">
                                @if (
                                    collect(request()->only([
                                        'search',
                                        'id_jenis',
                                        'id_lokasi',
                                        'status_aset'
                                    ]))->filter(
                                        fn ($value) => $value !== null && $value !== ''
                                    )->isNotEmpty()
                                )
                                    Data aset tidak ditemukan.
                                    Coba ubah kata pencarian atau filter.
                                @else
                                    Belum ada data aset.
                                    Tambahkan stok melalui menu Jenis Aset
                                    untuk membuat unit aset baru.
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
   GLOBAL
===================================================== */

.page-section {
    width: 100%;
    max-width: 1500px;
    margin: 20px auto 0;
    padding: 32px 40px 60px;
    box-sizing: border-box;
}

.page-section * {
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
    margin-bottom: 24px;
    background: #fff;
    border: 1px solid #d8dee8;
    border-radius: 8px;
    color: #315b91;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
    transition: .15s ease;
}

.back-link:hover {
    background: #f5f8fc;
    border-color: #315b91;
}

.page-eyebrow {
    display: block;
    margin-bottom: 6px;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
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
    line-height: 1.6;
}

/* =====================================================
   INFORMASI DATA
===================================================== */

.data-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 17px 22px;
    margin-bottom: 20px;
    background: #fff;
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

/* =====================================================
   KARTU TABEL
===================================================== */

.table-card {
    min-width: 0;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px 22px;
    border-bottom: 1px solid #e2e8f0;
}

.table-heading {
    min-width: 0;
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
    line-height: 1.5;
}

/* =====================================================
   PENCARIAN
===================================================== */

.search-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.search-form input {
    width: 240px;
    min-width: 0;
    height: 42px;
    padding: 0 12px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
    background: #fff;
    color: #334155;
    font-family: inherit;
    font-size: 13px;
}

.search-form input:focus,
.filter-field select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, .08);
}

.search-button,
.filter-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 16px;
    border: none;
    border-radius: 7px;
    background: #2563eb;
    color: #fff;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s ease;
}

.search-button:hover,
.filter-button:hover {
    background: #1d4ed8;
}

/* =====================================================
   FILTER
===================================================== */

.filter-form {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr)) auto;
    align-items: end;
    gap: 14px;
    padding: 18px 22px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}

.filter-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 0;
}

.filter-field label {
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}

.filter-field select {
    width: 100%;
    min-width: 0;
    height: 42px;
    padding: 0 10px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
    background: #fff;
    color: #334155;
    font-family: inherit;
    font-size: 13px;
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
}

.filter-button,
.reset-button {
    min-height: 42px;
    white-space: nowrap;
}

.filter-button {
    padding: 0 13px;
    font-size: 12px;
}

.reset-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 14px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #fff;
    color: #475569;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: background .2s ease;
}

.reset-button:hover {
    background: #f1f5f9;
}

/* =====================================================
   TABEL DATA
===================================================== */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
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
    padding: 14px 16px;
    color: #fff;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

td {
    padding: 16px;
    border-top: 1px solid #f1f5f9;
    color: #475569;
    font-size: 13px;
    vertical-align: middle;
}

tbody tr {
    transition: background .15s ease;
}

tbody tr:hover {
    background: #f8fafc;
}

.kode-badge {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.asset-name {
    color: #07549f;
    font-size: 13px;
    font-weight: 600;
}

.stock-date {
    min-width: 145px;
    white-space: nowrap;
    color: #475569;
    font-size: 12px;
}

.condition-badge,
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.condition-badge.good,
.status-badge.available {
    background: #dcfce7;
    color: #166534;
}

.condition-badge.minor,
.status-badge.repair {
    background: #fff3cd;
    color: #946200;
}

.condition-badge.damaged,
.status-badge.borrowed {
    background: #fee2e2;
    color: #991b1b;
}

.status-badge.inactive {
    background: #f1f5f9;
    color: #64748b;
}

/* =====================================================
   TOMBOL AKSI
===================================================== */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 36px;
    padding: 0 10px;
    border-radius: 7px;
    background: #dbeafe;
    color: #07549f;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.detail-button:hover {
    background: #bfdbfe;
}

.edit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    background: #fff3c4;
    color: #9a6b00;
    border-radius: 7px;
    text-decoration: none;
    font-size: 17px;
    transition: .2s ease;
}

.edit-button:hover {
    background: #fde68a;
    transform: translateY(-1px);
}

/* =====================================================
   DATA KOSONG DAN NOTIFIKASI
===================================================== */

.empty-data {
    padding: 45px 20px !important;
    color: #94a3b8;
    text-align: center;
    font-size: 14px;
    line-height: 1.7;
}

.alert-success,
.alert-error {
    width: calc(100% - 80px);
    max-width: 1420px;
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

.alert-error ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

/* =====================================================
   LAPTOP KECIL DAN TABLET
===================================================== */

@media (max-width: 1100px) {
    .page-section {
        padding: 32px 24px 45px;
    }

    .table-header {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .search-form {
        width: 100%;
    }

    .search-form input {
        flex: 1;
        width: auto;
    }

    .filter-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-actions {
        grid-column: 1 / -1;
        justify-content: flex-start;
    }
}

/* =====================================================
   HP
===================================================== */

@media (max-width: 700px) {
    .page-section {
        padding: 30px 15px 35px;
        margin-top: 10px;
    }

    .page-top {
        margin-bottom: 22px;
    }

    .back-link {
        margin-bottom: 20px;
        font-size: 12px;
    }

    .page-heading h1 {
        font-size: 26px;
    }

    .page-heading p {
        font-size: 13px;
    }

    .data-info {
        align-items: flex-start;
        flex-direction: column;
        gap: 5px;
        padding: 15px;
    }

    .info-right {
        display: none;
    }

    .table-header {
        padding: 17px 15px;
    }

    .table-header h3 {
        font-size: 15px;
    }

    .table-header p {
        font-size: 12px;
    }

    .search-form {
        display: flex;
        width: 100%;
        gap: 7px;
    }

    .search-form input {
        flex: 1;
        width: 0;
        min-width: 0;
        font-size: 12px;
    }

    .search-button {
        flex-shrink: 0;
        padding: 0 13px;
        font-size: 12px;
    }

    .filter-form {
        grid-template-columns: minmax(0, 1fr);
        gap: 13px;
        padding: 16px 15px;
    }

    .filter-field {
        width: 100%;
    }

    .filter-field select {
        font-size: 13px;
    }

    .filter-actions {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        grid-column: auto;
        gap: 8px;
        width: 100%;
    }

    .filter-button,
    .reset-button {
        width: 100%;
        min-width: 0;
        padding: 0 7px;
        font-size: 11px;
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        min-width: 1100px;
    }

    th,
    td {
        padding: 12px;
    }

    .alert-success,
    .alert-error {
        width: calc(100% - 30px);
        margin-top: 15px;
        padding: 12px 14px;
        font-size: 13px;
    }
}

/* =====================================================
   HP LAYAR KECIL
===================================================== */

@media (max-width: 380px) {
    .page-section {
        padding-right: 12px;
        padding-left: 12px;
    }

    .page-heading h1 {
        font-size: 23px;
    }

    .back-link {
        padding: 8px 11px;
    }

    .table-header {
        padding-right: 12px;
        padding-left: 12px;
    }

    .filter-form {
        padding-right: 12px;
        padding-left: 12px;
    }

    .filter-actions {
        gap: 6px;
    }

    .filter-button,
    .reset-button {
        font-size: 10px;
    }
}
</style>
@endpush
