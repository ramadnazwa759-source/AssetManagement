
@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="content-wrapper">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h4 class="page-title mb-1">Detail Pengembalian</h4>
                <p class="text-muted mb-0 small">
                    Informasi pencatatan pengembalian aset.
                </p>
            </div>

            <a href="{{ route('peminjaman.show', $pengembalian->id_peminjaman) }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Peminjaman
            </a>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- INFORMASI PENGEMBALIAN --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h6 class="fw-semibold mb-1">Informasi Pengembalian</h6>
                        <span class="text-muted small">
                            {{ $pengembalian->kode_pengembalian }}
                        </span>
                    </div>

                    <span class="badge bg-success-subtle text-success px-3 py-2">
                        Pengembalian Tercatat
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Kode Pengembalian</div>
                        <div class="info-value">
                            {{ $pengembalian->kode_pengembalian }}
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Kode Peminjaman</div>
                        <div class="info-value">
                            {{ $pengembalian->peminjaman->kode_peminjaman ?? '-' }}
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Tanggal Pengembalian</div>
                        <div class="info-value">
                            {{ $pengembalian->tanggal_pengembalian
                                ? \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d M Y')
                                : '-' }}
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Nama Peminjam</div>
                        <div class="info-value">
                            {{ $pengembalian->peminjaman->nama_peminjam ?? '-' }}
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Jumlah Barang Dikembalikan</div>
                        <div class="info-value">
                            {{ $pengembalian->jumlah_barang }} unit
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="info-label">Status Peminjaman</div>
                        <div class="info-value">
                            @php
                                $status = $pengembalian->peminjaman->status_peminjaman ?? '-';
                            @endphp

                            @if ($status === 'Dikembalikan')
                                <span class="badge bg-success-subtle text-success">
                                    {{ $status }}
                                </span>
                            @elseif ($status === 'Sebagian Dikembalikan')
                                <span class="badge bg-warning-subtle text-warning-emphasis">
                                    {{ $status }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    {{ $status }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($pengembalian->catatan)
                    <hr class="my-3">
                    <div class="info-label mb-1">Catatan Pengembalian</div>
                    <div class="small text-dark">
                        {{ $pengembalian->catatan }}
                    </div>
                @endif
            </div>
        </div>

        {{-- DAFTAR ASET --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-1">Daftar Aset yang Dikembalikan</h6>
                    <p class="text-muted small mb-0">
                        Rincian unit aset beserta kondisi setelah pengembalian.
                    </p>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="55">No.</th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Jenis Aset</th>
                                <th>Kondisi</th>
                                <th>Status Aset</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pengembalian->detailPengembalian as $index => $detail)
                                @php
                                    $kondisi = $detail->kondisi;
                                    $statusAset = $detail->aset->status_aset ?? '-';
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-medium">
                                        {{ $detail->id_aset }}
                                    </td>

                                    <td>
                                        {{ $detail->aset->nama_aset ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $detail->aset->jenis->nama_jenis ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($kondisi === 'Baik')
                                            <span class="badge bg-success-subtle text-success">
                                                {{ $kondisi }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                {{ $kondisi }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($statusAset === 'Tersedia')
                                            <span class="badge bg-success-subtle text-success">
                                                Tersedia
                                            </span>
                                        @elseif ($statusAset === 'Perlu Perbaikan')
                                            <span class="badge bg-warning-subtle text-warning-emphasis">
                                                Perlu Perbaikan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $statusAset }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Belum ada detail aset yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 p-3 rounded bg-light">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle text-primary mt-1"></i>
                        <div class="small text-muted">
                            Aset dengan kondisi <strong>Baik</strong> akan memiliki
                            status <strong>Tersedia</strong>. Sementara aset dengan
                            kondisi <strong>Rusak Ringan</strong> atau
                            <strong>Rusak Berat</strong> akan memiliki status
                            <strong>Perlu Perbaikan</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .content-wrapper {
        max-width: 1120px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 650;
        color: #263445;
    }

    .card {
        border-radius: 10px;
    }

    .info-label {
        color: #788493;
        font-size: 0.78rem;
        margin-bottom: 5px;
    }

    .info-value {
        color: #293747;
        font-size: 0.9rem;
        font-weight: 550;
        overflow-wrap: anywhere;
    }

    .table {
        font-size: 0.84rem;
    }

    .table th {
        color: #596779;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
        padding: 12px;
    }

    .table td {
        padding: 12px;
    }

    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.15rem;
        }

        .card-body {
            padding: 14px !important;
        }

        .info-value {
            font-size: 0.85rem;
        }

        .table {
            min-width: 650px;
        }
    }
</style>
@endsection
