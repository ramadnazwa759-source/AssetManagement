@extends('layouts.app')

@section('content')

@php
    $total = (int) $totalAset;

    $persen = fn ($jumlah) => $total > 0
        ? min(100, round(($jumlah / $total) * 100))
        : 0;

    $stats = [
        [
            'label' => 'Total Aset',
            'value' => $total,
            'note'  => 'Seluruh aset tercatat',
            'tone'  => 'blue',
        ],
        [
            'label' => 'Tersedia',
            'value' => $asetTersedia,
            'note'  => $persen($asetTersedia) . '% dari total',
            'tone'  => 'green',
        ],
        [
            'label' => 'Dipinjam',
            'value' => $asetDipinjam,
            'note'  => $persen($asetDipinjam) . '% dari total',
            'tone'  => 'amber',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'value' => $perluPerbaikan,
            'note'  => $persen($perluPerbaikan) . '% dari total',
            'tone'  => 'red',
        ],
    ];

    $masterData = [
        [
            'title' => 'Data Aset',
            'desc'  => 'Lihat dan kelola aset',
            'icon'  => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 9h8M8 13h8M8 17h5"/>',
            'link'  => route('aset.index'),
        ],
        [
            'title' => 'Kategori',
            'desc'  => 'Kategori & subkategori',
            'icon'  => '<path d="M4 5h7v7H4zM13 5h7v7h-7zM4 14h7v5H4zM13 14h7v5h-7z"/>',
            'link'  => route('kategori.index'),
        ],
        [
            'title' => 'Jenis Aset',
            'desc'  => 'Jenis dan stok aset',
            'icon'  => '<path d="M12 3 3 7.5 12 12l9-4.5L12 3ZM3 12l9 4.5 9-4.5M3 16.5 12 21l9-4.5"/>',
            'link'  => route('jenis-aset.index'),
        ],
        [
            'title' => 'Lokasi Aset',
            'desc'  => 'Tempat penyimpanan',
            'icon'  => '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
            'link'  => route('lokasi.index'),
        ],
        [
            'title' => 'Peminjaman',
            'desc'  => 'Kelola transaksi peminjaman aset',
            'icon'  => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4.5V3h6v1.5M9 10h6M9 14h6M9 18h3"/>',
            'link'  => route('peminjaman.index'),
        ],
    ];

    $subs = collect($subKategori)->take(8);
@endphp


<div class="dash">

    {{-- KONDISI ASET --}}
    <section class="dash-section dash-section-first">
        <div class="section-head">
            <h2>Kondisi Aset</h2>
            <a href="{{ route('aset.index') }}">Lihat data aset</a>
        </div>

        <div class="stat-grid">
            @foreach ($stats as $s)
                <div class="stat stat-{{ $s['tone'] }}">
                    <span class="stat-label">
                        <i class="stat-dot" aria-hidden="true"></i>
                        {{ $s['label'] }}
                    </span>

                    <strong class="stat-value">{{ $s['value'] }}</strong>

                    <span class="stat-note">{{ $s['note'] }}</span>
                </div>
            @endforeach
        </div>
    </section>


    {{-- KELOLA DATA --}}
    <section class="dash-section">
        <div class="section-head">
            <h2>Kelola Data</h2>
        </div>

        <div class="tile-grid">
            @foreach ($masterData as $item)
                <a href="{{ $item['link'] }}" class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            {!! $item['icon'] !!}
                        </svg>
                    </span>

                    <span class="tile-text">
                        <strong>{{ $item['title'] }}</strong>
                        <small>{{ $item['desc'] }}</small>
                    </span>
                </a>
            @endforeach
        </div>
    </section>


    {{-- SUBKATEGORI --}}
    <section class="dash-section">
        <div class="section-head">
            <h2>Subkategori Aset</h2>
            <a href="{{ route('kategori.index') }}">Lihat semua</a>
        </div>

        <div class="panel">
            @if ($subs->isNotEmpty())
                <div class="sub-grid">
                    @foreach ($subs as $item)
                        @php
                            $namaSub = $item->nama_subkategori ?? $item->nama ?? '-';
                        @endphp

                        <a href="#" class="sub-item">
                            <span class="sub-avatar">
                                @if (!empty($item->gambar))
                                    <img
                                        src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="{{ $namaSub }}"
                                    >
                                @else
                                    <span class="sub-initial">
                                        {{ strtoupper(substr($namaSub, 0, 1)) }}
                                    </span>
                                @endif
                            </span>

                            <span class="sub-name">{{ $namaSub }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="empty">Belum ada data subkategori aset.</p>
            @endif
        </div>
    </section>

</div>

@endsection


@push('styles')
<style>

/* =========================================================
   DASHBOARD
========================================================= */

.dash {
    --text: #1f2937;
    --muted: #6b7280;
    --border: #e3e8ef;
    --primary: #1f5a96;

    width: calc(100% - 48px);
    max-width: 1280px;
    margin: 0 auto;
    padding: 28px 0 56px;

    color: var(--text);
}

.dash *,
.dash *::before,
.dash *::after {
    box-sizing: border-box;
}

.dash h1,
.dash h2,
.dash p {
    margin: 0;
}

/* Footer tidak dipakai di halaman ini */
footer {
    display: none !important;
}

/* ---------- Section ---------- */

.dash-section {
    margin-top: 28px;
}

.dash-section-first {
    margin-top: 0;
}

.section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 12px;
}

.section-head h2 {
    font-size: 15px;
    font-weight: 600;
}

.section-head a {
    font-size: 13px;
    font-weight: 500;
    color: var(--primary);
    text-decoration: none;
}

.section-head a:hover {
    text-decoration: underline;
}

/* ---------- Gaya kartu bersama ---------- */

.stat,
.tile,
.panel {
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow:
        0 1px 2px rgba(30, 58, 95, .05),
        0 4px 14px rgba(30, 58, 95, .06);
}

/* =========================================================
   KARTU STATISTIK
========================================================= */

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.stat {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 16px 18px;
}

.stat-blue  { background: #f2f6fb; border-color: #dce6f2; }
.stat-green { background: #f1f9f4; border-color: #d9eee1; }
.stat-amber { background: #fdf7ea; border-color: #f3e5c3; }
.stat-red   { background: #fcf1f0; border-color: #f4d8d6; }

.stat-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
}

.stat-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.stat-blue  .stat-label { color: #2f5f93; }
.stat-green .stat-label { color: #3b7a57; }
.stat-amber .stat-label { color: #94671a; }
.stat-red   .stat-label { color: #a94742; }

.stat-blue  .stat-dot { background: #6d9bcb; }
.stat-green .stat-dot { background: #6fb88b; }
.stat-amber .stat-dot { background: #dfb35a; }
.stat-red   .stat-dot { background: #d9807b; }

.stat-value {
    font-size: 30px;
    font-weight: 650;
    line-height: 1.1;
    letter-spacing: -.02em;
    color: var(--text);
}

.stat-note {
    font-size: 12px;
    color: var(--muted);
}

/* =========================================================
   MENU KELOLA DATA
========================================================= */

.tile-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.tile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafd 100%);
    color: inherit;
    text-decoration: none;
    transition: border-color .15s ease, box-shadow .15s ease;
}

.tile:hover {
    border-color: #b9cde4;
    box-shadow:
        0 1px 2px rgba(30, 58, 95, .06),
        0 6px 18px rgba(30, 58, 95, .10);
}

.tile:focus-visible,
.sub-item:focus-visible,
.section-head a:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

.tile-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #e8f0f9;
    color: var(--primary);
}

.tile-icon svg {
    width: 19px;
    height: 19px;
}

.tile-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.tile-text strong {
    overflow: hidden;
    font-size: 14px;
    font-weight: 600;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.tile-text small {
    overflow: hidden;
    font-size: 12px;
    color: var(--muted);
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* =========================================================
   SUBKATEGORI
========================================================= */

.panel {
    padding: 14px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafd 100%);
}

.sub-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.sub-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    max-width: 100%;
    padding: 5px 14px 5px 5px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: #fff;
    color: inherit;
    text-decoration: none;
    transition: background .15s ease, border-color .15s ease;
}

.sub-item:hover {
    border-color: #b9cde4;
    background: #edf3fa;
}

.sub-avatar {
    display: block;
    flex-shrink: 0;
    width: 26px;
    height: 26px;
    overflow: hidden;
    border-radius: 50%;
    background: #e8f0f9;
}

.sub-avatar img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sub-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: var(--primary);
    font-size: 12px;
    font-weight: 600;
}

.sub-name {
    min-width: 0;
    overflow: hidden;
    font-size: 13px;
    font-weight: 500;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.empty {
    padding: 24px 12px;
    font-size: 13px;
    color: var(--muted);
    text-align: center;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {
    .stat-grid,
    .tile-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 720px) {
    .dash {
        width: calc(100% - 32px);
        padding-top: 20px;
    }

    .stat-grid,
    .tile-grid {
        gap: 10px;
    }

    .stat {
        padding: 14px;
    }

    .stat-value {
        font-size: 26px;
    }
}

@media (max-width: 480px) {
    .tile-grid {
        grid-template-columns: 1fr;
    }
}

@media (prefers-reduced-motion: reduce) {
    .dash * {
        transition: none !important;
    }
}

</style>
@endpush
