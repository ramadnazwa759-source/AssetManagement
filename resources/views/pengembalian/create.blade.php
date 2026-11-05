
@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="content-wrapper">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h4 class="page-title">Pengembalian Aset</h4>
                <p class="page-subtitle">
                    Catat pengembalian unit dari transaksi peminjaman.
                </p>
            </div>

            <a href="{{ route('peminjaman.show', $peminjaman->id_peminjaman) }}"
               class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Pengembalian belum dapat disimpan.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- INFORMASI PEMINJAMAN --}}
        <div class="section-card">
            <div class="section-heading">
                <div class="heading-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div>
                    <h5>Informasi Peminjaman</h5>
                    <p>Detail transaksi peminjaman aset.</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span>Kode Peminjaman</span>
                    <strong>{{ $peminjaman->kode_peminjaman }}</strong>
                </div>

                <div class="info-item">
                    <span>Nama Peminjam</span>
                    <strong>{{ $peminjaman->nama_peminjam }}</strong>
                </div>

                <div class="info-item">
                    <span>Tanggal Peminjaman</span>
                    <strong>
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>Rencana Pengembalian</span>
                    <strong>
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d/m/Y') }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>Tujuan Peminjaman</span>
                    <strong>{{ $peminjaman->tujuan }}</strong>
                </div>

                <div class="info-item">
                    <span>Status Peminjaman</span>
                    <span class="status-badge">
                        {{ $peminjaman->status_peminjaman }}
                    </span>
                </div>
            </div>
        </div>

        {{-- FORM PENGEMBALIAN --}}
        <form action="{{ route('pengembalian.store', $peminjaman->id_peminjaman) }}"
              method="POST"
              id="formPengembalian">
            @csrf

            {{-- DATA PENGEMBALIAN --}}
            <div class="section-card">
                <div class="section-heading">
                    <div class="heading-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <h5>Data Pengembalian</h5>
                        <p>Masukkan tanggal dan catatan pengembalian.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="tanggal_pengembalian">
                            Tanggal Pengembalian <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal_pengembalian"
                            id="tanggal_pengembalian"
                            class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                            value="{{ old('tanggal_pengembalian', now()->toDateString()) }}"
                            min="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d') }}"
                            required
                        >

                        @error('tanggal_pengembalian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="form-hint">
                            Minimal sesuai tanggal peminjaman.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan Pengembalian</label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            rows="2"
                            class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Masukkan catatan jika diperlukan..."
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- DAFTAR UNIT ASET --}}
            <div class="section-card">
                <div class="section-heading">
                    <div class="heading-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h5>Daftar Unit Aset</h5>
                        <p>Pilih unit dan tentukan kondisi saat dikembalikan.</p>
                    </div>
                </div>

                <div class="table-info">
                    <div>
                        <strong>{{ $unit->count() }} unit</strong>
                        <span>belum dikembalikan</span>
                    </div>

                    <div class="selection-info">
                        <span id="jumlahDipilih">0</span> unit dipilih
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table asset-table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" width="45">Pilih</th>
                                <th width="45">No.</th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Jenis Aset</th>
                                <th>Kondisi Saat Ini</th>
                                <th width="165">Kondisi Pengembalian</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($unit as $index => $detailUnit)
                                @php
                                    $kodeAset = $detailUnit->kode_aset;
                                    $oldCondition = old('unit.' . $kodeAset);
                                    $isChecked = $oldCondition !== null;
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input unit-checkbox"
                                            data-target="kondisi-{{ $index }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            aria-label="Pilih aset {{ $kodeAset }}"
                                        >
                                    </td>

                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <span class="asset-code">
                                            {{ $kodeAset }}
                                        </span>
                                    </td>

                                    <td class="asset-name">
                                        {{ $detailUnit->aset->nama_aset ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $detailUnit->detailPeminjaman->jenis->nama_jenis ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="condition-current">
                                            {{ $detailUnit->aset->kondisi_aset ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <select
                                            name="unit[{{ $kodeAset }}]"
                                            id="kondisi-{{ $index }}"
                                            class="form-select condition-select"
                                            {{ $isChecked ? '' : 'disabled' }}
                                        >
                                            <option value="">Pilih kondisi</option>

                                            <option value="Baik"
                                                {{ $oldCondition === 'Baik' ? 'selected' : '' }}>
                                                Baik
                                            </option>

                                            <option value="Rusak Ringan"
                                                {{ $oldCondition === 'Rusak Ringan' ? 'selected' : '' }}>
                                                Rusak Ringan
                                            </option>

                                            <option value="Rusak Berat"
                                                {{ $oldCondition === 'Rusak Berat' ? 'selected' : '' }}>
                                                Rusak Berat
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        Tidak ada unit yang dapat dikembalikan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="condition-note">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Informasi kondisi aset</strong>
                        <p>
                            Aset dengan kondisi Baik akan berstatus Tersedia.
                            Sementara itu, aset dengan kondisi Rusak Ringan
                            atau Rusak Berat akan berstatus Perlu Perbaikan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="form-actions">
                <a href="{{ route('peminjaman.show', $peminjaman->id_peminjaman) }}"
                   class="btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-check2-circle"></i>
                    Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* AREA KONTEN */
    .content-wrapper {
        max-width: 1120px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 22px;
    }

    .page-title {
        color: #1e3a5f;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 12px;
        margin: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        background: #fff;
        border: 1px solid #dbe3ef;
        border-radius: 6px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-back:hover,
    .btn-cancel:hover {
        background: #f1f5f9;
        color: #1e3a5f;
    }

    /* CARD */
    .section-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .025);
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf1f6;
    }

    .heading-icon {
        width: 33px;
        height: 33px;
        border-radius: 7px;
        background: #edf4ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .section-heading h5 {
        color: #1e3a5f;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 3px;
    }

    .section-heading p {
        color: #64748b;
        font-size: 11px;
        margin: 0;
    }

    /* INFORMASI PEMINJAMAN */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px 22px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 0;
    }

    .info-item > span:first-child {
        color: #64748b;
        font-size: 11px;
    }

    .info-item strong {
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .status-badge {
        display: inline-block;
        width: fit-content;
        background: #edf4ff;
        color: #2563eb;
        padding: 4px 9px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 600;
    }

    /* FORM */
    .form-grid {
        display: grid;
        grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
        gap: 18px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group label {
        display: block;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 6px;
        padding: 8px 10px;
        color: #334155;
        font-size: 12px;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, .1);
    }

    .form-hint {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: 10px;
    }

    /* TABEL UNIT */
    .table-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 11px;
        font-size: 11px;
    }

    .table-info strong {
        color: #1e40af;
        font-weight: 700;
    }

    .table-info > div:first-child span {
        color: #64748b;
        margin-left: 3px;
    }

    .selection-info {
        background: #eff6ff;
        color: #2563eb;
        padding: 5px 9px;
        border-radius: 5px;
        white-space: nowrap;
    }

    .selection-info span {
        font-weight: 700;
    }

    .asset-table {
        margin: 0;
        border: 1px solid #e2e8f0;
        min-width: 790px;
    }

    .asset-table thead th {
        background: #f3f7fd;
        color: #334e73;
        border-bottom: 1px solid #dce6f2;
        font-size: 10px;
        font-weight: 700;
        padding: 10px 9px;
        white-space: nowrap;
    }

    .asset-table tbody td {
        color: #475569;
        font-size: 11px;
        padding: 10px 9px;
        border-color: #edf1f6;
        vertical-align: middle;
    }

    .asset-table tbody tr:hover {
        background: #f8fbff;
    }

    .asset-name {
        color: #334155 !important;
        font-weight: 600;
    }

    .asset-code {
        color: #2563eb;
        font-weight: 600;
        white-space: nowrap;
    }

    .condition-current {
        display: inline-block;
        padding: 4px 7px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 4px;
        font-size: 10px;
        white-space: nowrap;
    }

    .condition-select {
        min-width: 135px;
        padding: 6px 8px;
        font-size: 11px;
    }

    .condition-select:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .form-check-input {
        width: 15px;
        height: 15px;
        margin: 0;
        cursor: pointer;
        border-color: #94a3b8;
    }

    .form-check-input:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .empty-state {
        text-align: center;
        padding: 25px !important;
        color: #94a3b8 !important;
    }

    /* CATATAN KONDISI */
    .condition-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        background: #f4f8ff;
        border: 1px solid #e1ebfa;
        border-radius: 6px;
        padding: 10px 12px;
        margin-top: 13px;
        color: #2563eb;
    }

    .condition-note i {
        font-size: 14px;
        margin-top: 1px;
    }

    .condition-note strong {
        display: block;
        color: #334e73;
        font-size: 11px;
        margin-bottom: 3px;
    }

    .condition-note p {
        color: #64748b;
        font-size: 10px;
        line-height: 1.5;
        margin: 0;
    }

    /* TOMBOL */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 4px;
        margin-bottom: 15px;
    }

    .btn-cancel,
    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border-radius: 6px;
        padding: 8px 13px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #dbe3ef;
        color: #475569;
    }

    .btn-submit {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #fff;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .content-wrapper {
            max-width: 100%;
        }

        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .section-card {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            align-items: flex-start;
        }

        .page-title {
            font-size: 18px;
        }

        .page-subtitle {
            max-width: 230px;
            line-height: 1.5;
        }

        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 10px;
        }

        .info-item strong {
            font-size: 11px;
        }

        .section-card {
            padding: 13px;
            border-radius: 8px;
        }

        .section-heading {
            margin-bottom: 14px;
        }

        .table-info {
            align-items: flex-start;
        }

        .form-actions {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
        }

        .btn-cancel,
        .btn-submit {
            padding: 9px 8px;
            font-size: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        const jumlahDipilih = document.getElementById('jumlahDipilih');
        const form = document.getElementById('formPengembalian');

        function updateSelection() {
            let total = 0;

            checkboxes.forEach(function (checkbox) {
                const select = document.getElementById(checkbox.dataset.target);

                select.disabled = !checkbox.checked;

                if (checkbox.checked) {
                    total++;
                } else {
                    select.value = '';
                }
            });

            jumlahDipilih.textContent = total;
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelection);
        });

        updateSelection();

        form.addEventListener('submit', function (event) {
            const selected = document.querySelectorAll('.unit-checkbox:checked');

            if (selected.length === 0) {
                event.preventDefault();
                alert('Pilih minimal satu unit aset yang akan dikembalikan.');
                return;
            }

            for (const checkbox of selected) {
                const select = document.getElementById(checkbox.dataset.target);

                if (!select.value) {
                    event.preventDefault();
                    alert('Pilih kondisi pengembalian untuk setiap unit yang dipilih.');
                    select.focus();
                    return;
                }
            }
        });
    });
</script>
@endsection