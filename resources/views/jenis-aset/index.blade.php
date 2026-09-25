@extends('layouts.app')

@section('content')

{{-- =========================================================
     HALAMAN JENIS ASET
========================================================= --}}

<section class="page-section">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="page-top">

        <div class="page-heading">

            <a href="/dashboard" class="back-link">
                ← Kembali ke Overview
            </a>

            <span class="page-label">
                MASTER DATA
            </span>

            <h1>
                Jenis Aset
            </h1>

            <p>
                Kelola jenis aset yang digunakan dalam kegiatan
                dan operasional Kalisawah Adventure.
            </p>

        </div>


        <div class="page-action">

            <a href="{{ route('jenis-aset.create') }}" class="add-button">
                <span>+</span>
                Tambah Jenis Aset
            </a>

        </div>

    </div>


    {{-- =====================================================
         INFORMASI DATA
    ====================================================== --}}

    <div class="data-info">

        <div class="info-left">

            <strong>
                {{ $jenis->count() }}
            </strong>

            <span>
                Jenis aset terdaftar
            </span>

        </div>

        <div class="info-right">
            Data master jenis aset
        </div>

    </div>


    {{-- =====================================================
         TABEL JENIS ASET
    ====================================================== --}}

    <div class="table-card">


        <div class="table-header">

            <div>

                <h2>
                    Daftar Jenis Aset
                </h2>

                <p>
                    Data jenis aset yang telah terdaftar.
                </p>

            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Kode
                        </th>

                        <th>
                            Nama Jenis Aset
                        </th>

                        <th>
                            Stok
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Deskripsi
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($jenis as $index => $item)

                        <tr>


                            {{-- NO --}}

                            <td>
                                {{ $index + 1 }}
                            </td>


                            {{-- KODE --}}

                            <td>

                                <span class="asset-code">
                                    {{ $item->id_jenis }}
                                </span>

                            </td>


                            {{-- NAMA --}}

                            <td>

                                <div class="asset-name">
                                    {{ $item->nama_jenis }}
                                </div>

                            </td>


                            {{-- STOK --}}

                            <td>

                                <span class="jumlah">
                                    {{ $item->stok }}
                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if ($item->status_jenis === 'Aktif')

                                    <span class="status-badge active">
                                        Aktif
                                    </span>

                                @else

                                    <span class="status-badge inactive">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>


                            {{-- DESKRIPSI --}}

                            <td>

                                <span class="description">
                                    {{ $item->deskripsi ?: '-' }}
                                </span>

                            </td>


                            {{-- AKSI --}}

                            <td>

                                <div class="action-group">


                                    {{-- TAMBAH STOK --}}

                                    <a
                                        href="{{ route('jenis-aset.edit', $item->id_jenis) }}"
                                        class="action-button stock"
                                    >
                                        + Stok
                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('jenis-aset.edit', $item->id_jenis) }}"
                                        class="action-button edit"
                                    >
                                        Edit
                                    </a>


                                    {{-- UBAH STATUS --}}

                                    <form
                                        action="{{ route('jenis-aset.status', $item->id_jenis) }}"
                                        method="POST"
                                        style="display: inline;"
                                    >

                                        @csrf

                                        @method('PATCH')

                                        @if ($item->status_jenis === 'Aktif')

                                            <button
                                                type="submit"
                                                class="action-button deactivate"
                                            >
                                                Nonaktifkan
                                            </button>

                                        @else

                                            <button
                                                type="submit"
                                                class="action-button activate"
                                            >
                                                Aktifkan
                                            </button>

                                        @endif

                                    </form>


                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="empty-data">

                                Belum ada jenis aset yang terdaftar.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</section>


@endsection


{{-- =========================================================
     STYLE
========================================================= --}}

@push('styles')

<style>

.page-section {

    width: 86%;

    max-width: 1250px;

    margin: 45px auto 60px;

}


.page-top {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    gap: 30px;

    margin-bottom: 25px;

}


.page-heading {

    max-width: 650px;

}


.back-link {

    display: inline-block;

    margin-bottom: 18px;

    color: #7d8994;

    font-size: 12px;

    font-weight: 500;

    text-decoration: none;

}


.back-link:hover {

    color: #0d4f8b;

}


.page-label {

    display: block;

    margin-bottom: 7px;

    color: #7e8b97;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.6px;

}


.page-heading h1 {

    margin: 0 0 8px;

    color: #263746;

    font-size: 30px;

    font-weight: 700;

}


.page-heading p {

    margin: 0;

    color: #7d8994;

    font-size: 13px;

    line-height: 1.6;

}


.add-button {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 16px;

    background: #0d4f8b;

    border-radius: 9px;

    color: #fff;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;

    transition: .2s ease;

}


.add-button:hover {

    background: #0a4377;

    transform: translateY(-1px);

}


.add-button span {

    font-size: 17px;

    line-height: 1;

}


.data-info {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 13px 17px;

    margin-bottom: 14px;

    background: #eef5fa;

    border-radius: 10px;

}


.info-left {

    display: flex;

    align-items: center;

    gap: 8px;

}


.info-left strong {

    color: #0d4f8b;

    font-size: 17px;

}


.info-left span {

    color: #657582;

    font-size: 11px;

}


.info-right {

    color: #8a97a2;

    font-size: 10px;

}


.table-card {

    background: #fff;

    border: 1px solid #e5ebef;

    border-radius: 14px;

    overflow: hidden;

}


.table-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 18px 20px;

    border-bottom: 1px solid #edf1f3;

}


.table-header h2 {

    margin: 0 0 4px;

    color: #263746;

    font-size: 16px;

    font-weight: 600;

}


.table-header p {

    margin: 0;

    color: #8a97a2;

    font-size: 11px;

}


.table-wrapper {

    width: 100%;

    overflow-x: auto;

}


table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

}


thead {

    background: #f8fafb;

}


th {

    padding: 12px 16px;

    color: #6f7d89;

    font-size: 10px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

    border-bottom: 1px solid #e7edf1;

}


td {

    padding: 14px 16px;

    color: #52616d;

    font-size: 12px;

    vertical-align: middle;

    border-bottom: 1px solid #edf1f3;

}


tbody tr:last-child td {

    border-bottom: none;

}


tbody tr:hover {

    background: #fafcfd;

}


.asset-code {

    display: inline-block;

    padding: 5px 8px;

    background: #edf5fb;

    border-radius: 6px;

    color: #0d4f8b;

    font-size: 10px;

    font-weight: 600;

}


.asset-name {

    color: #263746;

    font-weight: 600;

}


.jumlah {

    color: #263746;

    font-weight: 600;

}


.status-badge {

    display: inline-block;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: 600;

}


.status-badge.active {

    background: #edf7f1;

    color: #28784d;

}


.status-badge.inactive {

    background: #f1f2f3;

    color: #737d85;

}


.description {

    display: block;

    max-width: 280px;

    color: #7d8994;

    font-size: 11px;

    line-height: 1.5;

}


.action-group {

    display: flex;

    align-items: center;

    gap: 5px;

    flex-wrap: wrap;

}


.action-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 6px 8px;

    border: none;

    border-radius: 6px;

    font-size: 10px;

    font-family: inherit;

    font-weight: 600;

    text-decoration: none;

    white-space: nowrap;

    cursor: pointer;

    transition: .2s ease;

}


.action-button:hover {

    opacity: .75;

}


.action-button.stock {

    background: #edf7f1;

    color: #28784d;

}


.action-button.edit {

    background: #edf5fb;

    color: #0d4f8b;

}


.action-button.deactivate {

    background: #fff4e8;

    color: #b66a19;

}


.action-button.activate {

    background: #edf7f1;

    color: #28784d;

}


.empty-data {

    padding: 50px 20px;

    color: #9aa5ae;

    text-align: center;

}


@media (max-width: 900px) {

    .page-top {

        align-items: flex-start;

        flex-direction: column;

    }


    .page-action {

        width: 100%;

    }


    .add-button {

        justify-content: center;

    }

}


@media (max-width: 600px) {

    .page-section {

        width: 90%;

        margin-top: 30px;

    }


    .page-heading h1 {

        font-size: 26px;

    }


    .data-info {

        align-items: flex-start;

        flex-direction: column;

        gap: 5px;

    }


    .table-header {

        padding: 15px;

    }


    th,
    td {

        padding: 11px 12px;

    }

}

</style>

@endpush