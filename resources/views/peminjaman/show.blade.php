
@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@push('styles')
<style>
    .page-wrapper {
        padding: 20px;
        color: #334155;
        font-size: 13px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .page-header h2 {
        font-size: 19px;
        font-weight: 700;
        margin: 0 0 4px;
        color: #1e293b;
    }

    .page-header p {
        font-size: 12px;
        color: #8491a3;
        margin: 0;
    }

    .btn-back {
        padding: 8px 12px;
        border: 1px solid #cbdaf0;
        border-radius: 7px;
        background: #eff6ff;
        color: #2563a8;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-back:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .content-card {
        background: #fff;
        border: 1px solid #e2eaf4;
        border-top: 3px solid #8bb5e8;
        border-radius: 9px;
        padding: 17px;
        margin-bottom: 14px;
        box-shadow: 0 2px 7px rgba(30, 64, 100, .035);
    }

    .card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 11px;
        border-bottom: 1px solid #edf2f8;
    }

    .card-heading h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        color: #263f60;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px 22px;
    }

    .info-label {
        display: block;
        color: #8292a8;
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .info-value {
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        overflow-wrap: anywhere;
        line-height: 1.5;
    }

    .highlight-value {
        color: #2563a8;
        font-weight: 700;
    }

    .normal {
        font-weight: 400;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-dipinjam {
        background: #fff1d6;
        color: #a16207;
        border: 1px solid #f5d99d;
    }

    .status-dikembalikan {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #b7ebc7;
    }

    .status-dibatalkan {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .summary-item {
        position: relative;
        padding: 13px 14px;
        border: 1px solid #dce9f8;
        border-radius: 8px;
        background: #f5f9ff;
        overflow: hidden;
    }

    .summary-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #5794d5;
    }

    .summary-item:nth-child(2) {
        background: #f0f8ff;
        border-color: #d4eafa;
    }

    .summary-item:nth-child(2)::before {
        background: #2696c9;
    }

    .summary-item:nth-child(3) {
        background: #f0faf5;
        border-color: #d5eee0;
    }

    .summary-item:nth-child(3)::before {
        background: #35a675;
    }

    .summary-item span {
        display: block;
        font-size: 11px;
        color: #647b96;
        margin-bottom: 6px;
    }

    .summary-item strong {
        font-size: 17px;
        color: #285582;
        font-weight: 700;
    }

    .summary-item:nth-child(3) strong {
        color: #23845d;
    }

    .table-wrapper {
        overflow-x: auto;
        border: 1px solid #e5edf6;
        border-radius: 7px;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 580px;
    }

    .detail-table th {
        background: #edf5ff;
        color: #355d88;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        padding: 11px 12px;
        border-bottom: 1px solid #dce8f5;
        white-space: nowrap;
    }

    .detail-table td {
        padding: 11px 12px;
        font-size: 12px;
        color: #475569;
        border-bottom: 1px solid #edf1f5;
        background: #fff;
    }

    .detail-table tbody tr:hover td {
        background: #f7faff;
    }

    .detail-table tbody tr:last-child td {
        border-bottom: none;
    }

    .asset-section + .asset-section {
        border-top: 1px solid #e5edf6;
        margin-top: 18px;
        padding-top: 17px;
    }

    .asset-title {
        display: inline-block;
        font-size: 13px;
        font-weight: 700;
        color: #285582;
        background: #edf5ff;
        border-left: 3px solid #5794d5;
        border-radius: 4px;
        padding: 7px 11px;
        margin-bottom: 8px;
    }

    .asset-subtitle {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 11px;
    }

    .asset-subtitle strong {
        color: #334155;
        font-weight: 700;
    }

    .note-box {
        padding: 11px 13px;
        background: #f5f8fc;
        border: 1px solid #e1e9f3;
        border-left: 3px solid #8bb5e8;
        border-radius: 7px;
        font-size: 12px;
        color: #475569;
        line-height: 1.6;
        white-space: pre-line;
    }

    .btn-cancel {
        padding: 8px 12px;
        border: 1px solid #f1bcbc;
        border-radius: 7px;
        background: #fff1f1;
        color: #c03939;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .btn-cancel:hover {
        background: #fee2e2;
    }

    .cancel-card {
        background: #fffafa;
        border: 1px solid #f4dada;
        border-top: 3px solid #e58b8b;
    }

    .cancel-card .card-heading {
        border-bottom-color: #f4e2e2;
    }

    .cancel-card .card-heading h3 {
        color: #a63d3d;
    }

    .cancel-description {
        font-size: 11px;
        color: #8b6666;
        margin: 0;
        line-height: 1.6;
    }

    /* Modal pembatalan */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, .5);
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 410px;
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, .15);
        border-top: 4px solid #dc4444;
    }

    .modal-box h3 {
        font-size: 16px;
        margin: 0 0 7px;
        color: #1e293b;
    }

    .modal-description {
        font-size: 12px;
        color: #718096;
        line-height: 1.6;
        margin: 0 0 16px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 7px;
        color: #475569;
    }

    .form-textarea {
        width: 100%;
        min-height: 85px;
        padding: 10px;
        border: 1px solid #dce3eb;
        border-radius: 7px;
        font-family: inherit;
        font-size: 12px;
        resize: vertical;
        outline: none;
        box-sizing: border-box;
    }

    .form-textarea:focus {
        border-color: #7898c8;
        box-shadow: 0 0 0 3px rgba(120, 152, 200, .12);
    }

    .form-error {
        font-size: 11px;
        color: #dc2626;
        margin-top: 5px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 17px;
    }

    .btn-close-modal,
    .btn-confirm {
        padding: 8px 13px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-close-modal {
        border: 1px solid #dce3eb;
        background: #fff;
        color: #475569;
    }

    .btn-confirm {
        border: none;
        background: #dc4444;
        color: #fff;
    }

    .btn-confirm:hover {
        background: #c83232;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #94a3b8;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .page-wrapper {
            padding: 14px;
        }

        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            align-items: flex-start;
        }

        .card-heading {
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h2>Detail Peminjaman</h2>
            <p>Informasi transaksi peminjaman aset.</p>
        </div>

        <a href="{{ route('peminjaman.index') }}" class="btn-back">
            &larr; Kembali
        </a>
    </div>

    {{-- Informasi Transaksi --}}
    <div class="content-card">
        <div class="card-heading">
            <h3>Informasi Transaksi</h3>

            @if ($peminjaman->status_peminjaman === 'Dipinjam')
                <span class="status-badge status-dipinjam">Dipinjam</span>
            @elseif ($peminjaman->status_peminjaman === 'Dikembalikan')
                <span class="status-badge status-dikembalikan">Dikembalikan</span>
            @elseif ($peminjaman->status_peminjaman === 'Dibatalkan')
                <span class="status-badge status-dibatalkan">Dibatalkan</span>
            @endif
        </div>

        <div class="info-grid">
            <div>
                <span class="info-label">Kode Peminjaman</span>
                <div class="info-value highlight-value">{{ $peminjaman->kode_peminjaman }}</div>
            </div>

            <div>
                <span class="info-label">Nama Peminjam</span>
                <div class="info-value">{{ $peminjaman->nama_peminjam }}</div>
            </div>

            <div>
                <span class="info-label">Tanggal Peminjaman</span>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}
                </div>
            </div>

            <div>
                <span class="info-label">Rencana Pengembalian</span>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d/m/Y') }}
                </div>
            </div>

            <div>
                <span class="info-label">Tujuan Peminjaman</span>
                <div class="info-value normal">{{ $peminjaman->tujuan }}</div>
            </div>

            <div>
                <span class="info-label">Catatan</span>
                <div class="info-value normal">{{ $peminjaman->catatan ?: '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="content-card">
        <div class="card-heading">
            <h3>Ringkasan Peminjaman</h3>
        </div>

        <div class="summary-grid">
            <div class="summary-item">
                <span>Jenis Aset</span>
                <strong>{{ $peminjaman->detailPeminjaman->count() }} jenis</strong>
            </div>

            <div class="summary-item">
                <span>Total Unit Dipinjam</span>
                <strong>{{ $peminjaman->detailPeminjaman->sum('jumlah_pinjam') }} unit</strong>
            </div>

            <div class="summary-item">
                <span>Total Unit Dikembalikan</span>
                <strong>{{ $peminjaman->detailPeminjaman->sum('jumlah_dikembalikan') }} unit</strong>
            </div>
        </div>
    </div>

    {{-- Detail Aset --}}
    <div class="content-card">
        <div class="card-heading">
            <h3>Detail Aset yang Dipinjam</h3>
        </div>

        @forelse ($peminjaman->detailPeminjaman as $index => $detail)
            <div class="asset-section">
                <div class="asset-title">
                    {{ $index + 1 }}. {{ $detail->jenis->nama_jenis ?? 'Jenis aset tidak ditemukan' }}
                </div>

                <div class="asset-subtitle">
                    Dipinjam: <strong>{{ $detail->jumlah_pinjam }} unit</strong>
                    &nbsp; | &nbsp;
                    Dikembalikan: <strong>{{ $detail->jumlah_dikembalikan }} unit</strong>
                </div>

                <div class="table-wrapper">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Aset</th>
                                <th>Kondisi</th>
                                <th>Status Unit</th>
                                <th>Tanggal Dikembalikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detail->detailUnit as $unitIndex => $unit)
                                <tr>
                                    <td>{{ $unitIndex + 1 }}</td>
                                    <td><strong style="color:#285582;">{{ $unit->kode_aset }}</strong></td>
                                    <td>{{ $unit->aset->kondisi_aset ?? '-' }}</td>
                                    <td>
                                        @if ($unit->status_unit === 'Dipinjam')
                                            <span class="status-badge status-dipinjam">Dipinjam</span>
                                        @elseif ($unit->status_unit === 'Dikembalikan')
                                            <span class="status-badge status-dikembalikan">Dikembalikan</span>
                                        @elseif ($unit->status_unit === 'Dibatalkan')
                                            <span class="status-badge status-dibatalkan">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $unit->tanggal_dikembalikan
                                            ? \Carbon\Carbon::parse($unit->tanggal_dikembalikan)->format('d/m/Y')
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        Belum ada unit aset yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="empty-state">
                Belum ada detail aset pada transaksi ini.
            </div>
        @endforelse
    </div>

    {{-- Pembatalan --}}
    @if ($peminjaman->status_peminjaman === 'Dipinjam')
        <div class="content-card cancel-card">
            <div class="card-heading" style="margin-bottom:8px;">
                <h3>Pembatalan Peminjaman</h3>
                <button type="button" class="btn-cancel" onclick="openCancelModal()">
                    Batalkan Peminjaman
                </button>
            </div>

            <p class="cancel-description">
                Pembatalan berlaku untuk seluruh transaksi peminjaman beserta unit aset yang tercatat di dalamnya.
            </p>
        </div>
    @elseif ($peminjaman->status_peminjaman === 'Dibatalkan')
        <div class="content-card cancel-card">
            <div class="card-heading">
                <h3>Informasi Pembatalan</h3>
                <span class="status-badge status-dibatalkan">Dibatalkan</span>
            </div>

            <span class="info-label">Alasan Pembatalan</span>
            <div class="note-box">
                {{ $peminjaman->catatan ?: 'Tidak ada alasan pembatalan yang tercatat.' }}
            </div>
        </div>
    @endif

</div>

{{-- Modal Konfirmasi Pembatalan --}}
@if ($peminjaman->status_peminjaman === 'Dipinjam')
    <div class="modal-overlay" id="cancelModal">
        <div class="modal-box">
            <h3>Konfirmasi Pembatalan</h3>

            <p class="modal-description">
                Apakah kamu yakin ingin membatalkan seluruh transaksi peminjaman
                <strong>{{ $peminjaman->kode_peminjaman }}</strong>?
                Seluruh unit aset dalam transaksi ini akan diproses sebagai batal.
            </p>

            <form
                action="{{ route('peminjaman.batal', $peminjaman->id_peminjaman) }}"
                method="POST"
            >
                @csrf

                <label for="catatan" class="form-label">
                    Alasan Pembatalan <span style="color:#dc2626;">*</span>
                </label>

                <textarea
                    name="catatan"
                    id="catatan"
                    class="form-textarea"
                    maxlength="1000"
                    required
                    placeholder="Tuliskan alasan pembatalan..."
                >{{ old('catatan') }}</textarea>

                @error('catatan')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                <div class="modal-actions">
                    <button type="button" class="btn-close-modal" onclick="closeCancelModal()">
                        Kembali
                    </button>

                    <button type="submit" class="btn-confirm">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.add('show');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.remove('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('cancelModal');

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeCancelModal();
                }
            });

            @if ($errors->has('catatan'))
                openCancelModal();
            @endif
        }
    });
</script>
@endsection
