@extends('layouts.app')

@section('content')

@php

$masterData = [

    [
        'title' => 'Kategori & Subkategori',
        'description' => 'Kelola pengelompokan aset',
        'icon' => '▦',
        'link' => '#',
    ],

    [
        'title' => 'Jenis Aset',
        'description' => 'Kelola jenis dan stok aset',
        'icon' => '▤',
        'link' => '/jenis-aset',
    ],

    [
        'title' => 'Lokasi Aset',
        'description' => 'Kelola lokasi penyimpanan aset',
        'icon' => '⌖',
        'link' => '#',
    ],

    [
        'title' => 'Data Aset',
        'description' => 'Lihat dan kelola data aset',
        'icon' => '▣',
        'link' => '#',
    ],

];

@endphp


{{-- =========================================================
     HERO / HEADER
========================================================= --}}

<section class="hero-section">

    <div class="hero-content">

        <span class="hero-label">
            ASSET MANAGEMENT SYSTEM
        </span>

        <h1>
            Kelola aset,<br>
            lebih terarah.
        </h1>

        <p>
            Kelola data aset Kalisawah Adventure dengan lebih
            terstruktur, mudah dipantau, dan terdokumentasi.
        </p>

        <div class="hero-actions">
            <a href="#" class="primary-button">
                Kelola Data Aset
                <span>→</span>
            </a>

            <a href="#" class="secondary-button">
                Lihat Riwayat
            </a>
        </div>

    </div>

    <div class="hero-decoration">
        <div class="hero-circle circle-one"></div>
        <div class="hero-circle circle-two"></div>
        <div class="hero-circle circle-three"></div>
    </div>

</section>


{{-- =========================================================
     RINGKASAN DATA ASET
========================================================= --}}

<section class="section">

    <div class="section-header">

        <div>
            <span class="section-label">
                RINGKASAN
            </span>

            <h2>
                Kondisi Aset
            </h2>
        </div>

        <a href="#" class="section-link">
            Lihat data aset →
        </a>

    </div>


    <div class="summary-grid">

        {{-- TOTAL ASET --}}
        <div class="summary-item">

            <span class="summary-label">
                Total Aset
            </span>

            <strong class="summary-value">
                {{ $totalAset }}
            </strong>

            <span class="summary-description">
                Seluruh aset yang tercatat
            </span>

        </div>


        {{-- ASET TERSEDIA --}}
        <div class="summary-item">

            <span class="summary-label">
                Tersedia
            </span>

            <strong class="summary-value">
                {{ $asetTersedia }}
            </strong>

            <span class="summary-description">
                Aset yang siap digunakan
            </span>

        </div>


        {{-- ASET DIPINJAM --}}
        <div class="summary-item">

            <span class="summary-label">
                Dipinjam
            </span>

            <strong class="summary-value">
                {{ $asetDipinjam }}
            </strong>

            <span class="summary-description">
                Aset yang sedang dipinjam
            </span>

        </div>


        {{-- PERLU PERBAIKAN --}}
        <div class="summary-item">

            <span class="summary-label">
                Perlu Perbaikan
            </span>

            <strong class="summary-value">
                {{ $perluPerbaikan }}
            </strong>

            <span class="summary-description">
                Aset dengan kondisi rusak
            </span>

        </div>

    </div>

</section>


{{-- =========================================================
     MASTER DATA
========================================================= --}}

<section class="section">

    <div class="section-header">

        <div>
            <span class="section-label">
                MASTER DATA
            </span>

            <h2>
                Kelola Data
            </h2>
        </div>

    </div>


    <div class="master-grid">

        @foreach ($masterData as $item)

            <a href="{{ $item['link'] }}" class="master-item">

                <div class="master-icon">
                    {{ $item['icon'] }}
                </div>

                <div class="master-content">

                    <h3>
                        {{ $item['title'] }}
                    </h3>

                    <p>
                        {{ $item['description'] }}
                    </p>

                </div>

                <span class="master-arrow">
                    →
                </span>

            </a>

        @endforeach

    </div>

</section>


{{-- =========================================================
     SUBKATEGORI ASET
========================================================= --}}

<section class="section">

    <div class="section-header">

        <div>
            <span class="section-label">
                KATEGORI ASET
            </span>

            <h2>
                Subkategori Aset
            </h2>
        </div>

        <a href="#" class="section-link">
            Lihat semua →
        </a>

    </div>


    <div class="subcategory-list">

        @forelse ($subKategori as $item)

            <a href="#" class="subcategory-item">

                {{-- GAMBAR BULAT KECIL --}}
                <div class="subcategory-image">

                    @if (!empty($item->gambar))
                        <img
                            src="{{ asset('storage/' . $item->gambar) }}"
                            alt="{{ $item->nama_subkategori }}"
                        >
                    @else
                        <div class="subcategory-placeholder">
                            {{ strtoupper(substr($item->nama_subkategori, 0, 1)) }}
                        </div>
                    @endif

                </div>


                {{-- INFORMASI SUBKATEGORI --}}
                <div class="subcategory-info">

                    <span class="subcategory-category">
                        Subkategori Aset
                    </span>

                    <h3>
                        {{ $item->nama_subkategori }}
                    </h3>

                </div>


                {{-- ARROW --}}
                <span class="subcategory-arrow">
                    →
                </span>

            </a>

        @empty

            <div class="subcategory-empty">
                Belum ada data subkategori aset.
            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
     ALUR PENGELOLAAN ASET
========================================================= --}}

<section class="section">

    <div class="section-header">

        <div>
            <span class="section-label">
                PENGELOLAAN
            </span>

            <h2>
                Alur Pengelolaan Aset
            </h2>
        </div>

    </div>


    <div class="asset-flow">

        <div class="flow-item">

            <div class="flow-number">
                01
            </div>

            <div>
                <h3>
                    Data Aset
                </h3>

                <p>
                    Data aset tersimpan secara terstruktur
                    berdasarkan jenis dan lokasi.
                </p>
            </div>

        </div>


        <div class="flow-item">

            <div class="flow-number">
                02
            </div>

            <div>
                <h3>
                    Peminjaman
                </h3>

                <p>
                    Aset yang digunakan dapat dicatat
                    melalui proses peminjaman.
                </p>
            </div>

        </div>


        <div class="flow-item">

            <div class="flow-number">
                03
            </div>

            <div>
                <h3>
                    Pengembalian
                </h3>

                <p>
                    Kondisi aset dicatat kembali setelah
                    proses pengembalian.
                </p>
            </div>

        </div>


        <div class="flow-item">

            <div class="flow-number">
                04
            </div>

            <div>
                <h3>
                    Riwayat
                </h3>

                <p>
                    Perubahan dan penggunaan aset
                    dapat ditelusuri melalui riwayat.
                </p>
            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     QUICK ACCESS
========================================================= --}}

<section class="quick-section">

    <div class="quick-content">

        <div>

            <span class="section-label">
                AKSES CEPAT
            </span>

            <h2>
                Mulai kelola aset
            </h2>

            <p>
                Pilih menu yang ingin kamu kelola.
            </p>

        </div>


        <div class="quick-actions">

            <a href="#" class="quick-button">
                Data Aset
                <span>→</span>
            </a>

            <a href="#" class="quick-button">
                Peminjaman
                <span>→</span>
            </a>

            <a href="#" class="quick-button">
                Riwayat
                <span>→</span>
            </a>

        </div>

    </div>

</section>


@endsection


{{-- =========================================================
     STYLE
========================================================= --}}

@push('styles')

<style>

/* =========================================================
   GLOBAL
========================================================= */

* {
    box-sizing: border-box;
}

body {
    background: #f7f9fb;
    color: #263746;
}


/* =========================================================
   HERO
========================================================= */

.hero-section {
    position: relative;
    overflow: hidden;

    min-height: 330px;

    display: flex;
    align-items: center;

    padding: 55px 7%;

    background:
        linear-gradient(
            135deg,
            #0d4f8b 0%,
            #1767a9 100%
        );

    border-radius: 0 0 28px 28px;

    color: #fff;
}

.hero-content {
    position: relative;
    z-index: 2;

    max-width: 650px;
}

.hero-label {
    display: inline-block;

    margin-bottom: 14px;

    font-size: 11px;
    font-weight: 700;

    letter-spacing: 2px;

    color: rgba(255,255,255,.7);
}

.hero-section h1 {
    margin: 0 0 15px;

    font-size: clamp(34px, 4vw, 52px);

    line-height: 1.08;

    font-weight: 700;
}

.hero-section p {
    max-width: 560px;

    margin: 0 0 25px;

    font-size: 15px;

    line-height: 1.7;

    color: rgba(255,255,255,.82);
}

.hero-actions {
    display: flex;

    gap: 10px;

    flex-wrap: wrap;
}

.primary-button,
.secondary-button {
    display: inline-flex;

    align-items: center;

    gap: 10px;

    padding: 11px 17px;

    border-radius: 9px;

    font-size: 13px;
    font-weight: 600;

    text-decoration: none;

    transition: .2s ease;
}

.primary-button {
    background: #fff;

    color: #0d4f8b;
}

.primary-button:hover {
    transform: translateY(-2px);
}

.secondary-button {
    border: 1px solid rgba(255,255,255,.35);

    color: #fff;

    background: rgba(255,255,255,.08);
}

.secondary-button:hover {
    background: rgba(255,255,255,.15);
}


/* =========================================================
   HERO DECORATION
========================================================= */

.hero-decoration {
    position: absolute;

    right: 5%;
    top: 0;

    width: 420px;
    height: 100%;
}

.hero-circle {
    position: absolute;

    border-radius: 50%;

    border: 1px solid rgba(255,255,255,.12);
}

.circle-one {
    width: 330px;
    height: 330px;

    right: 20px;
    top: -40px;
}

.circle-two {
    width: 220px;
    height: 220px;

    right: 75px;
    top: 15px;
}

.circle-three {
    width: 100px;
    height: 100px;

    right: 135px;
    top: 75px;

    background: rgba(255,255,255,.05);
}


/* =========================================================
   SECTION
========================================================= */

.section {
    width: 86%;

    max-width: 1250px;

    margin: 45px auto 0;
}

.section-header {
    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 18px;
}

.section-label {
    display: block;

    margin-bottom: 5px;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.6px;

    color: #7e8b97;
}

.section-header h2 {
    margin: 0;

    font-size: 22px;

    color: #263746;
}

.section-link {
    color: #0d4f8b;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;
}

.section-link:hover {
    text-decoration: underline;
}


/* =========================================================
   SUMMARY
========================================================= */

.summary-grid {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;
}

.summary-item {
    padding: 17px 18px;

    background: #fff;

    border: 1px solid #e7edf2;

    border-radius: 13px;
}

.summary-label {
    display: block;

    margin-bottom: 8px;

    font-size: 12px;

    color: #7e8b97;
}

.summary-value {
    display: block;

    margin-bottom: 5px;

    font-size: 28px;

    line-height: 1;

    color: #0d4f8b;
}

.summary-description {
    font-size: 11px;

    color: #9aa5ae;
}


/* =========================================================
   MASTER DATA
========================================================= */

.master-grid {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;
}

.master-item {
    display: flex;

    align-items: center;

    gap: 11px;

    min-height: 76px;

    padding: 12px 14px;

    background: #fff;

    border: 1px solid #e7edf2;

    border-radius: 13px;

    text-decoration: none;

    color: inherit;

    transition: .2s ease;
}

.master-item:hover {
    border-color: #0d4f8b;

    transform: translateY(-2px);
}

.master-icon {
    width: 38px;
    height: 38px;

    min-width: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #edf5fb;

    color: #0d4f8b;

    font-size: 18px;
}

.master-content {
    flex: 1;

    min-width: 0;
}

.master-content h3 {
    margin: 0 0 3px;

    font-size: 13px;

    font-weight: 600;

    color: #263746;
}

.master-content p {
    margin: 0;

    font-size: 10px;

    line-height: 1.4;

    color: #8b98a4;
}

.master-arrow {
    font-size: 16px;

    color: #9aa5ae;
}


/* =========================================================
   SUBKATEGORI
========================================================= */

.subcategory-list {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;
}

.subcategory-item {
    display: flex;

    align-items: center;

    gap: 12px;

    min-height: 72px;

    padding: 10px 13px;

    background: #fff;

    border: 1px solid #e7edf2;

    border-radius: 13px;

    text-decoration: none;

    color: inherit;

    transition: .2s ease;
}

.subcategory-item:hover {
    border-color: #0d4f8b;

    transform: translateY(-2px);
}


/* FOTO BULAT KECIL */

.subcategory-image {
    width: 46px;
    height: 46px;

    min-width: 46px;

    overflow: hidden;

    border-radius: 50%;

    background: #edf2f5;
}

.subcategory-image img {
    width: 100%;
    height: 100%;

    display: block;

    object-fit: cover;
}


/* INFORMASI */

.subcategory-info {
    flex: 1;

    min-width: 0;
}

.subcategory-category {
    display: block;

    margin-bottom: 3px;

    font-size: 10px;

    color: #8b98a4;
}

.subcategory-info h3 {
    margin: 0;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: 13px;

    font-weight: 600;

    color: #263746;
}

.subcategory-arrow {
    font-size: 17px;

    color: #9aa5ae;
}


/* =========================================================
   ASSET FLOW
========================================================= */

.asset-flow {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;
}

.flow-item {
    display: flex;

    gap: 12px;

    padding: 17px;

    background: #fff;

    border: 1px solid #e7edf2;

    border-radius: 13px;
}

.flow-number {
    width: 31px;
    height: 31px;

    min-width: 31px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #edf5fb;

    color: #0d4f8b;

    font-size: 10px;

    font-weight: 700;
}

.flow-item h3 {
    margin: 0 0 5px;

    font-size: 13px;

    font-weight: 600;
}

.flow-item p {
    margin: 0;

    font-size: 10px;

    line-height: 1.6;

    color: #8b98a4;
}


/* =========================================================
   QUICK ACCESS
========================================================= */

.quick-section {
    width: 86%;

    max-width: 1250px;

    margin: 45px auto;

    padding: 28px;

    background: #eef5fa;

    border-radius: 18px;
}

.quick-content {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 25px;
}

.quick-content h2 {
    margin: 0 0 6px;

    font-size: 21px;
}

.quick-content p {
    margin: 0;

    font-size: 12px;

    color: #7d8a95;
}

.quick-actions {
    display: flex;

    gap: 8px;

    flex-wrap: wrap;
}

.quick-button {
    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 10px 13px;

    background: #fff;

    border: 1px solid #dfe8ee;

    border-radius: 9px;

    color: #0d4f8b;

    font-size: 11px;

    font-weight: 600;

    text-decoration: none;

    transition: .2s ease;
}

.quick-button:hover {
    border-color: #0d4f8b;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1000px) {

    .summary-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .master-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .subcategory-list {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .asset-flow {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .hero-decoration {
        opacity: .5;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 650px) {

    .hero-section {
        min-height: auto;

        padding: 40px 7%;
    }

    .hero-section h1 {
        font-size: 34px;
    }

    .hero-section p {
        font-size: 13px;
    }

    .hero-decoration {
        display: none;
    }

    .section {
        width: 90%;

        margin-top: 35px;
    }

    .section-header {
        align-items: flex-start;

        flex-direction: column;

        gap: 8px;
    }

    .summary-grid {
        grid-template-columns:
            repeat(2, 1fr);

        gap: 8px;
    }

    .summary-item {
        padding: 14px;
    }

    .summary-value {
        font-size: 23px;
    }

    .master-grid {
        grid-template-columns: 1fr;
    }

    .subcategory-list {
        grid-template-columns: 1fr;
    }

    .asset-flow {
        grid-template-columns: 1fr;
    }

    .quick-section {
        width: 90%;

        padding: 20px;
    }

    .quick-content {
        flex-direction: column;

        align-items: flex-start;
    }

    .quick-actions {
        width: 100%;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .subcategory-image {
        width: 42px;
        height: 42px;

        min-width: 42px;
    }

}

.subcategory-placeholder {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #edf5fb;
    color: #0d4f8b;

    font-size: 15px;
    font-weight: 700;
}

</style>

@endpush