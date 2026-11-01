
@extends('layouts.app')

@section('content')

<div class="detail-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('aset.index') }}" class="back-button">
                ←
            </a>

            <div>
                <span class="page-eyebrow">DATA ASET</span>

                <h1>Detail Unit Aset</h1>

                <p>
                    Informasi lengkap unit aset yang telah terdaftar.
                </p>
            </div>
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

    {{-- DETAIL CARD --}}
    <div class="detail-card">

        {{-- IDENTITAS ASET --}}
        <div class="section-heading">
            <div>
                <h2>Informasi Aset</h2>
                <p>Identitas dan informasi dasar unit aset.</p>
            </div>

            <span class="status-badge
                {{ $aset->status_aset === 'Tersedia' ? 'status-tersedia' : '' }}
                {{ $aset->status_aset === 'Dipinjam' ? 'status-dipinjam' : '' }}
                {{ $aset->status_aset === 'Perlu Perbaikan' ? 'status-perbaikan' : '' }}
                {{ $aset->status_aset === 'Nonaktif' ? 'status-nonaktif' : '' }}">
                {{ $aset->status_aset }}
            </span>
        </div>

        {{-- GAMBAR --}}
        @if ($aset->gambar)
            <div class="asset-image">
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($aset->gambar) }}"
                    alt="Gambar {{ $aset->nama_aset }}"
                >
            </div>
        @else
            <div class="image-placeholder">
                <span>Tidak ada gambar aset</span>
            </div>
        @endif

        {{-- DATA --}}
        <div class="detail-grid">

            <div class="detail-item">
                <span class="detail-label">Kode Aset</span>
                <span class="detail-value">{{ $aset->kode_aset }}</span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Nama Aset</span>
                <span class="detail-value">{{ $aset->nama_aset }}</span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Jenis Aset</span>
                <span class="detail-value">
                    {{ $aset->jenis->nama_jenis ?? '-' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Kategori</span>
                <span class="detail-value">
                    {{ $aset->jenis?->subKategori?->kategori?->nama_kategori ?? '-' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Sub Kategori</span>
                <span class="detail-value">
                    {{ $aset->jenis?->subKategori?->nama_sub_kategori ?? '-' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Lokasi Aset</span>
                <span class="detail-value">
                    {{ $aset->lokasi->nama_lokasi ?? 'Belum ditentukan' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Tanggal Beli</span>
                <span class="detail-value">
                    {{ $aset->tanggal_beli ? $aset->tanggal_beli->format('d/m/Y') : '-' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Kondisi Aset</span>
                <span class="detail-value">
                    {{ $aset->kondisi_aset }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Status Aset</span>
                <span class="detail-value">
                    {{ $aset->status_aset }}
                </span>
            </div>

        </div>

        {{-- KETERANGAN --}}
        <div class="description-section">
            <span class="detail-label">Keterangan</span>

            <div class="description-content">
                @if ($aset->keterangan)
                    {{ $aset->keterangan }}
                @else
                    <span class="empty-description">
                        Tidak ada keterangan untuk aset ini.
                    </span>
                @endif
            </div>
        </div>

        {{-- CATATAN --}}
        <div class="form-note">
            <strong>Informasi:</strong>

            Halaman ini menampilkan detail satu unit aset. Perubahan informasi
            dapat dilakukan melalui halaman edit sesuai field yang diizinkan.
        </div>

        {{-- BUTTON --}}
        <div class="form-actions">
            <a
                href="{{ route('aset.index') }}"
                class="cancel-button"
            >
                Kembali
            </a>

            <a
                href="{{ route('aset.edit', $aset->kode_aset) }}"
                class="edit-button"
            >
                Edit Aset
            </a>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
/* PAGE */
.detail-page {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    padding: 65px 30px 60px;
    box-sizing: border-box;
}

/* HEADER */
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
    flex-shrink: 0;
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

/* ALERT */
.alert {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 7px;
    font-size: 13px;
    line-height: 1.5;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* DETAIL CARD */
.detail-card {
    width: 100%;
    max-width: 850px;
    margin: 0 auto;
    padding: 28px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-sizing: border-box;
}

/* SECTION HEADING */
.section-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
}

.section-heading h2 {
    margin: 0;
    color: #0f172a;
    font-size: 19px;
    font-weight: 700;
}

.section-heading p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

/* STATUS */
.status-badge {
    display: inline-block;
    flex-shrink: 0;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-tersedia {
    background: #dcfce7;
    color: #166534;
}

.status-dipinjam {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-perbaikan {
    background: #fef3c7;
    color: #92400e;
}

.status-nonaktif {
    background: #f1f5f9;
    color: #475569;
}

/* IMAGE */
.asset-image {
    margin-bottom: 26px;
}

.asset-image img {
    display: block;
    width: 100%;
    max-height: 320px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 150px;
    margin-bottom: 26px;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    color: #94a3b8;
    font-size: 13px;
}

/* DETAIL GRID */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 28px;
    border-top: 1px solid #e2e8f0;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 7px;
    padding: 17px 0;
    border-bottom: 1px solid #e2e8f0;
    min-width: 0;
}

.detail-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}

.detail-value {
    color: #1e293b;
    font-size: 14px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

/* DESCRIPTION */
.description-section {
    margin-top: 24px;
}

.description-content {
    margin-top: 9px;
    padding: 14px;
    min-height: 55px;
    box-sizing: border-box;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    color: #334155;
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-line;
    overflow-wrap: anywhere;
}

.empty-description {
    color: #94a3b8;
    font-style: italic;
}

/* NOTE */
.form-note {
    margin-top: 24px;
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

/* BUTTON */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.cancel-button,
.edit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
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

.edit-button {
    background: #2563eb;
    color: white;
    border: 1px solid #2563eb;
}

.edit-button:hover {
    background: #1d4ed8;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .detail-page {
        padding: 100px 20px 40px;
    }

    .detail-card {
        padding: 20px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .section-heading {
        flex-direction: column;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-button,
    .edit-button {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>
@endpush
