
@extends('layouts.app')

@section('content')

<div class="page-container">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <span class="page-label">TRANSAKSI ASET</span>
            <h1>Data Peminjaman</h1>
            <p>Kelola dan pantau seluruh transaksi peminjaman aset.</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                Kembali ke Dashboard
            </a>

            <a href="{{ route('peminjaman.create') }}" class="btn-primary">
                <span>+</span> Tambah Peminjaman
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="summary-card">
        <div>
            <span class="summary-label">Total Transaksi</span>
            <div class="summary-value">{{ $peminjaman->total() }}</div>
            <span class="summary-note">Transaksi sesuai pencarian dan filter</span>
        </div>
        <div class="summary-icon">▤</div>
    </div>

    {{-- DAFTAR TRANSAKSI --}}
    <div class="content-card">

        <div class="content-header">
            <div>
                <h2>Daftar Transaksi Peminjaman</h2>
                <p>Informasi peminjam, aset yang dipinjam, dan status transaksi.</p>
            </div>
        </div>

        {{-- PENCARIAN DAN FILTER --}}
        <form action="{{ route('peminjaman.index') }}" method="GET" class="filter-section">

            <div class="search-field">
                <label for="search">Pencarian</label>
                <div class="search-input">
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode, nama peminjam, atau jenis aset..."
                        maxlength="100"
                    >
                    <button type="submit" class="btn-search">Cari</button>
                </div>
            </div>

            <div class="filter-grid">

                <div class="form-field">
                    <label for="tanggal_mulai">Dari Tanggal</label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        id="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}"
                    >
                </div>

                <div class="form-field">
                    <label for="tanggal_akhir">Sampai Tanggal</label>
                    <input
                        type="date"
                        name="tanggal_akhir"
                        id="tanggal_akhir"
                        value="{{ request('tanggal_akhir') }}"
                    >
                </div>

                <div class="form-field">
                    <label for="status_peminjaman">Status Peminjaman</label>
                    <select name="status_peminjaman" id="status_peminjaman">
                        <option value="">Semua Status</option>

                        @foreach ([
                            'Dipinjam',
                            'Sebagian Dikembalikan',
                            'Dikembalikan',
                            'Dibatalkan'
                        ] as $status)
                            <option
                                value="{{ $status }}"
                                @selected(request('status_peminjaman') === $status)
                            >
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter">Terapkan Filter</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn-reset">Reset</a>
                </div>

            </div>
        </form>

        {{-- INFORMASI HASIL --}}
        <div class="result-info">
            Menampilkan
            <strong>{{ $peminjaman->firstItem() ?? 0 }}–{{ $peminjaman->lastItem() ?? 0 }}</strong>
            dari
            <strong>{{ $peminjaman->total() }}</strong>
            transaksi
        </div>

        {{-- TABEL --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="55">No.</th>
                        <th>Kode Peminjaman</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Pengembalian</th>
                        <th>Jenis Aset</th>
                        <th class="text-center">Jumlah Unit</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td>
                                {{ $peminjaman->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="transaction-code">
                                    {{ $item->kode_peminjaman }}
                                </span>
                            </td>

                            <td>
                                <span class="borrower-name">
                                    {{ $item->nama_peminjam }}
                                </span>
                            </td>

                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}
                            </td>

                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="asset-list">
                                    @forelse ($item->detailPeminjaman as $detail)
                                        <div class="asset-item">
                                            {{ $detail->jenis->nama_jenis ?? '-' }}
                                        </div>
                                    @empty
                                        <span class="muted">Tidak ada detail aset</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="quantity">
                                    {{ $item->detailPeminjaman->sum('jumlah_pinjam') }}
                                </span>
                                <span class="unit-label">unit</span>
                            </td>

                            <td>
                                @php
                                    $statusClass = match ($item->status_peminjaman) {
                                        'Dipinjam' => 'status-borrowed',
                                        'Sebagian Dikembalikan' => 'status-partial',
                                        'Dikembalikan' => 'status-returned',
                                        'Dibatalkan' => 'status-cancelled',
                                        default => 'status-default',
                                    };
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    <span class="status-dot"></span>
                                    {{ $item->status_peminjaman }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="action-buttons">

                                    <a
                                        href="{{ route('peminjaman.show', $item->id_peminjaman) }}"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                    @if (in_array($item->status_peminjaman, [
                                        'Dipinjam',
                                        'Sebagian Dikembalikan'
                                    ]))
                                        <a
                                            href="{{ route('pengembalian.create', $item->id_peminjaman) }}"
                                            class="btn-return"
                                        >
                                            Pengembalian
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-cell">
                                <div class="empty-state">
                                    <div class="empty-icon">▤</div>

                                    @if (
                                        request()->filled('search') ||
                                        request()->filled('tanggal_mulai') ||
                                        request()->filled('tanggal_akhir') ||
                                        request()->filled('status_peminjaman')
                                    )
                                        <strong>Data peminjaman tidak ditemukan</strong>
                                        <p>Coba ubah kata pencarian atau filter yang digunakan.</p>
                                        <a href="{{ route('peminjaman.index') }}" class="empty-reset">
                                            Hapus Filter
                                        </a>
                                    @else
                                        <strong>Belum ada transaksi peminjaman</strong>
                                        <p>Transaksi peminjaman yang ditambahkan akan ditampilkan di sini.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($peminjaman->hasPages())
            <div class="pagination-container">
                {{ $peminjaman->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
.page-container {
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    padding: 32px 40px 55px;
    box-sizing: border-box;
    color: #334155;
}

.page-container * {
    box-sizing: border-box;
}

/* HEADER */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.page-label {
    display: block;
    margin-bottom: 7px;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
}

.page-header h1 {
    margin: 0;
    color: #172b4d;
    font-size: 27px;
    font-weight: 700;
}

.page-header p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 13px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 17px;
    border-radius: 7px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 17px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #fff;
    color: #475569;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-secondary:hover {
    background: #f1f5f9;
    color: #334155;
}

.btn-primary:hover,
.btn-search:hover,
.btn-filter:hover {
    background: #1d4ed8;
    color: white;
}

/* SUMMARY */
.summary-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 19px 22px;
    margin-bottom: 20px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
}

.summary-label {
    color: #64748b;
    font-size: 13px;
}

.summary-value {
    margin: 5px 0;
    color: #1e40af;
    font-size: 26px;
    font-weight: 700;
}

.summary-note {
    color: #94a3b8;
    font-size: 11px;
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 9px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 23px;
}

/* CONTENT */
.content-card {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.content-header {
    padding: 21px 22px;
    border-bottom: 1px solid #e2e8f0;
}

.content-header h2 {
    margin: 0;
    color: #172b4d;
    font-size: 16px;
    font-weight: 650;
}

.content-header p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 12px;
}

/* SEARCH AND FILTER */
.filter-section {
    padding: 19px 22px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.search-field {
    max-width: 600px;
    margin-bottom: 17px;
}

.search-field label,
.form-field label {
    display: block;
    margin-bottom: 7px;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}

.search-input {
    display: flex;
    gap: 8px;
}

.search-input input,
.form-field input,
.form-field select {
    width: 100%;
    min-width: 0;
    height: 41px;
    padding: 0 11px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    outline: none;
    background: #fff;
    color: #334155;
    font-family: inherit;
    font-size: 12px;
}

.search-input input:focus,
.form-field input:focus,
.form-field select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, .1);
}

.search-input input::placeholder {
    color: #94a3b8;
}

.btn-search,
.btn-filter {
    min-height: 41px;
    padding: 0 17px;
    border: none;
    border-radius: 6px;
    background: #2563eb;
    color: #fff;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr)) auto;
    align-items: end;
    gap: 13px;
}

.form-field {
    min-width: 0;
}

.filter-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 0 15px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #fff;
    color: #475569;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-reset:hover {
    background: #f1f5f9;
}

/* RESULT INFO */
.result-info {
    padding: 13px 22px;
    color: #64748b;
    font-size: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.result-info strong {
    color: #334155;
}

/* TABLE */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
}

thead {
    background: #f1f5f9;
}

th {
    padding: 13px 14px;
    color: #475569;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

td {
    padding: 15px 14px;
    border-top: 1px solid #edf0f4;
    color: #475569;
    font-size: 12px;
    vertical-align: middle;
}

tbody tr:hover {
    background: #fafcff;
}

.text-center {
    text-align: center;
}

.transaction-code {
    color: #2563eb;
    font-size: 11px;
    font-weight: 650;
    white-space: nowrap;
}

.borrower-name {
    color: #334155;
    font-weight: 600;
}

.date-cell {
    white-space: nowrap;
    color: #64748b;
}

.asset-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.asset-item {
    color: #475569;
    line-height: 1.4;
}

.quantity {
    color: #334155;
    font-weight: 700;
}

.unit-label {
    color: #94a3b8;
    font-size: 10px;
}

.muted {
    color: #94a3b8;
}

/* STATUS */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 650;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.status-borrowed {
    background: #fff7e6;
    color: #a16207;
}

.status-partial {
    background: #eff6ff;
    color: #1d4ed8;
}

.status-returned {
    background: #ecfdf3;
    color: #15803d;
}

.status-cancelled {
    background: #fef2f2;
    color: #b91c1c;
}

.status-default {
    background: #f1f5f9;
    color: #64748b;
}

/* ACTION */
.action-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-detail,
.btn-return {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 33px;
    padding: 0 11px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-detail {
    border: 1px solid #bfdbfe;
    background: #eff6ff;
    color: #1d4ed8;
}

.btn-detail:hover {
    background: #dbeafe;
}

.btn-return {
    border: 1px solid #a7f3d0;
    background: #ecfdf5;
    color: #047857;
}

.btn-return:hover {
    background: #d1fae5;
}

/* EMPTY STATE */
.empty-cell {
    padding: 0;
}

.empty-state {
    padding: 48px 20px;
    text-align: center;
}

.empty-icon {
    margin-bottom: 12px;
    color: #94a3b8;
    font-size: 30px;
}

.empty-state strong {
    display: block;
    color: #334155;
    font-size: 14px;
}

.empty-state p {
    margin: 7px 0 0;
    color: #94a3b8;
    font-size: 12px;
}

.empty-reset {
    display: inline-block;
    margin-top: 13px;
    color: #2563eb;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
}

/* PAGINATION */
.pagination-container {
    display: flex;
    justify-content: flex-end;
    padding: 17px 22px;
    border-top: 1px solid #e2e8f0;
}

/* ALERT */
.alert {
    padding: 12px 16px;
    margin-bottom: 18px;
    border-radius: 7px;
    font-size: 13px;
}

.alert-success {
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    color: #166534;
}

.alert-error {
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #991b1b;
}

.alert-error ul {
    margin: 7px 0 0;
    padding-left: 20px;
}

/* RESPONSIVE */
@media (max-width: 1100px) {
    .page-container {
        padding: 28px 24px 45px;
    }

    .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-buttons {
        grid-column: 1 / -1;
    }
}

@media (max-width: 700px) {
    .page-container {
        padding: 24px 14px 35px;
    }

    .page-header {
        align-items: stretch;
        flex-direction: column;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions a {
        flex: 1;
    }

    .content-header,
    .filter-section {
        padding: 17px 14px;
    }

    .search-field {
        max-width: none;
    }

    .filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .filter-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-column: auto;
    }

    .btn-filter,
    .btn-reset {
        width: 100%;
    }

    .result-info {
        padding: 12px 14px;
    }

    .action-buttons {
        justify-content: flex-start;
    }

    .pagination-container {
        justify-content: flex-start;
        padding: 15px;
        overflow-x: auto;
    }
}
</style>
@endpush
