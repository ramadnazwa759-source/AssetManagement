@extends('layouts.app')

@section('content')

<style>
    .lokasi-container {
        padding: 90px 30px 30px;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* SEARCH */
    .search-card {
        width: 420px;
        margin-left: auto;
        margin-bottom: 25px;
        background: white;
        padding: 10px 12px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .search-form {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .search-form input {
        flex: 1;
        min-width: 0;
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        outline: none;
        font-size: 14px;
    }

    .search-form input:focus {
        border-color: #2563eb;
    }

    .search-button {
        border: none;
        background: #2563eb;
        color: white;
        padding: 9px 15px;
        border-radius: 7px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
    }

    .search-button:hover {
        background: #1d4ed8;
    }

    /* HEADER */
    .lokasi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .lokasi-title h1 {
        margin: 0;
        font-size: 28px;
        color: #1e3a8a;
    }

    .lokasi-title p {
        margin-top: 6px;
        color: #64748b;
    }

    .btn-tambah {
        background: #2563eb;
        color: white;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-tambah:hover {
        background: #1d4ed8;
    }

    /* ALERT */
    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    /* TOTAL LOKASI */
    .info-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .info-card h3 {
        margin: 0 0 5px;
        color: #64748b;
        font-size: 14px;
    }

    .total-lokasi {
        font-size: 28px;
        font-weight: bold;
        color: #1e3a8a;
    }

    /* TABLE */
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        margin-bottom: 20px;
    }

    .table-header h2 {
        margin: 0;
        font-size: 20px;
        color: #1e293b;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #eff6ff;
        color: #1e3a8a;
        text-align: left;
        padding: 13px;
        font-size: 14px;
    }

    td {
        padding: 13px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: middle;
    }

    tr:hover {
        background: #f8fafc;
    }

    /* ACTION */
    .action-buttons {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .btn-detail {
        background: #e0f2fe;
        color: #0369a1;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: #bae6fd;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #fde68a;
    }

    .btn-delete {
        background: #fee2e2;
        color: #b91c1c;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* EMPTY DATA */
    .empty-data {
        text-align: center;
        padding: 40px;
        color: #64748b;
    }

    /* MODAL DETAIL */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 500px;
        border-radius: 12px;
        padding: 25px;
        position: relative;
    }

    .modal-content h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #1e3a8a;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-item label {
        display: block;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 5px;
    }

    .detail-item div {
        padding: 10px 12px;
        background: #f8fafc;
        border-radius: 7px;
        color: #334155;
    }

    .close-modal {
        position: absolute;
        right: 18px;
        top: 12px;
        font-size: 25px;
        cursor: pointer;
        color: #64748b;
    }

    .close-modal:hover {
        color: #1e293b;
    }

    .btn-tutup {
        margin-top: 10px;
        background: #64748b;
        color: white;
        border: none;
        padding: 9px 15px;
        border-radius: 7px;
        cursor: pointer;
    }

    .btn-tutup:hover {
        background: #475569;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {

        .lokasi-container {
            padding: 90px 15px 30px;
        }

        .search-card {
            width: 100%;
            box-sizing: border-box;
        }

        .lokasi-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .search-form {
            flex-direction: row;
        }

        .search-button {
            width: auto;
        }

        .table-card {
            overflow-x: auto;
        }

        table {
            min-width: 800px;
        }
    }
</style>


<div class="lokasi-container">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- SEARCH --}}
    <div class="search-card">

        <form
            action="{{ route('lokasi.index') }}"
            method="GET"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama lokasi..."
            >

            <button type="submit" class="search-button">
                🔍 Cari
            </button>

        </form>

    </div>


    {{-- HEADER --}}
    <div class="lokasi-header">

        <div class="lokasi-title">

            <h1>Lokasi Aset</h1>

            <p>
                Kelola lokasi tetap yang menjadi identitas aset
            </p>

        </div>

        <a
            href="{{ route('lokasi.create') }}"
            class="btn-tambah"
        >
            + Tambah Lokasi
        </a>

    </div>


    {{-- TOTAL LOKASI --}}
    <div class="info-card">

        <h3>Total Lokasi</h3>

        <div class="total-lokasi">
            {{ $lokasi->count() }}
        </div>

    </div>


    {{-- DAFTAR LOKASI --}}
    <div class="table-card">

        <div class="table-header">
            <h2>Daftar Lokasi</h2>
        </div>


        @if($lokasi->count() > 0)

            <table>

                <thead>

                    <tr>
                        <th>ID LOKASI</th>
                        <th>NAMA LOKASI</th>
                        <th>DESKRIPSI</th>
                        <th>AKSI</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($lokasi as $item)

                        <tr>

                            {{-- ID --}}
                            <td>
                                {{ $item->id_lokasi }}
                            </td>


                            {{-- NAMA --}}
                            <td>
                                <strong>
                                    {{ $item->nama_lokasi }}
                                </strong>
                            </td>


                            {{-- DESKRIPSI --}}
                            <td>
                                {{ $item->deskripsi ?: '-' }}
                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- DETAIL --}}
                                    <button
                                        type="button"
                                        class="btn-detail"
                                        onclick="openDetail(
                                            '{{ $item->id_lokasi }}',
                                            '{{ addslashes($item->nama_lokasi) }}',
                                            '{{ addslashes($item->deskripsi ?: '-') }}'
                                        )"
                                    >
                                        Detail
                                    </button>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('lokasi.edit', $item->id_lokasi) }}"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('lokasi.destroy', $item->id_lokasi) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-delete"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-data">
                Belum ada data lokasi aset.
            </div>

        @endif

    </div>

</div>


{{-- MODAL DETAIL --}}
<div id="detailModal" class="modal">

    <div class="modal-content">

        <span
            class="close-modal"
            onclick="closeDetail()"
        >
            &times;
        </span>


        <h2>Detail Lokasi Aset</h2>


        {{-- ID --}}
        <div class="detail-item">

            <label>ID Lokasi</label>

            <div id="detailId"></div>

        </div>


        {{-- NAMA --}}
        <div class="detail-item">

            <label>Nama Lokasi</label>

            <div id="detailNama"></div>

        </div>


        {{-- DESKRIPSI --}}
        <div class="detail-item">

            <label>Deskripsi</label>

            <div id="detailDeskripsi"></div>

        </div>


        <button
            type="button"
            class="btn-tutup"
            onclick="closeDetail()"
        >
            Tutup
        </button>

    </div>

</div>


<script>

    function openDetail(id, nama, deskripsi) {

        document.getElementById('detailId').innerText = id;

        document.getElementById('detailNama').innerText = nama;

        document.getElementById('detailDeskripsi').innerText = deskripsi;

        document.getElementById('detailModal').style.display = 'flex';

    }


    function closeDetail() {

        document.getElementById('detailModal').style.display = 'none';

    }


    window.onclick = function(event) {

        const modal = document.getElementById('detailModal');

        if (event.target === modal) {

            modal.style.display = 'none';

        }

    }

</script>

@endsection