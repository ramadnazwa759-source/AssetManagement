@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert-success">
        ✓ {{ session('success') }}
    </div>
@endif


<section class="page-section">

    {{-- =====================================================
        HEADER
    ===================================================== --}}

    <div class="page-top">

        <div class="page-heading">

            <a href="{{ route('kategori.index') }}" class="back-link">
                ← Kembali ke Kategori Aset
            </a>

            <span class="page-eyebrow">
                MASTER DATA
            </span>

            <h1>Sub Kategori Aset</h1>

            <p>
                Kelola sub kategori dari kategori
                <strong>{{ $kategori->nama_kategori }}</strong>.
            </p>

        </div>


        <div class="page-action">

            <a
                href="{{ route('kategori.sub-kategori.create', $kategori->id_kategori) }}"
                class="add-button"
            >
                <span>+</span>
                Tambah Sub Kategori
            </a>

        </div>

    </div>


    {{-- =====================================================
        INFORMASI KATEGORI
    ===================================================== --}}

    <div class="data-info">

        <div class="info-left">

            <strong>
                {{ $subKategori->count() }}
            </strong>

            <span>
                Sub kategori terdaftar
            </span>

        </div>


        <div class="info-right">

            Kategori:
            <strong>
                {{ $kategori->nama_kategori }}
            </strong>

            <span>
                ({{ $kategori->id_kategori }})
            </span>

        </div>

    </div>


    {{-- =====================================================
        TABLE
    ===================================================== --}}

    <div class="table-card">

        <div class="table-header">

            <div>

                <h3>
                    Daftar Sub Kategori
                </h3>

                <p>
                    Daftar sub kategori yang termasuk dalam
                    kategori {{ $kategori->nama_kategori }}.
                </p>

            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>ID Sub Kategori</th>

                        <th>Nama Sub Kategori</th>

                        <th>Deskripsi</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($subKategori as $item)

                        <tr>

                            {{-- ID SUB KATEGORI --}}
                            <td>

                                <span class="kode-badge">
                                    {{ $item->id_sub_kategori }}
                                </span>

                            </td>


                            {{-- NAMA SUB KATEGORI --}}
                            <td>

                                <strong class="sub-kategori-name">
                                    {{ $item->nama_sub_kategori }}
                                </strong>

                            </td>


                            {{-- DESKRIPSI --}}
                            <td>

                                <span class="deskripsi-text">

                                    {{ $item->deskripsi ?: '-' }}

                                </span>

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="action-buttons">


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route(
                                            'kategori.sub-kategori.edit',
                                            [
                                                'id_kategori' => $kategori->id_kategori,
                                                'id' => $item->id_sub_kategori
                                            ]
                                        ) }}"
                                        class="edit-button"
                                        title="Ubah Sub Kategori"
                                    >
                                        ✎
                                    </a>


                                    {{-- HAPUS --}}
                                    <button
                                        type="button"
                                        class="delete-button"
                                        title="Hapus Sub Kategori"
                                        onclick="openDeleteModal(
                                            '{{ $item->id_sub_kategori }}',
                                            @js($item->nama_sub_kategori)
                                        )"
                                    >
                                        🗑
                                        <span>Hapus</span>
                                    </button>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="empty-data"
                            >

                                Belum ada sub kategori untuk
                                kategori ini.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</section>


{{-- =====================================================
    MODAL KONFIRMASI HAPUS
===================================================== --}}

<div
    id="deleteModal"
    class="delete-modal"
>

    <div class="delete-modal-content">


        {{-- ICON --}}

        <div class="delete-icon">
            🗑
        </div>


        {{-- JUDUL --}}

        <h3>
            Hapus Sub Kategori?
        </h3>


        {{-- PESAN --}}

        <p>

            Apakah kamu yakin ingin menghapus sub kategori

            <strong id="deleteSubCategoryName"></strong>?

        </p>


        {{-- TOMBOL --}}

        <div class="delete-modal-actions">


            {{-- TIDAK --}}

            <button
                type="button"
                class="cancel-delete"
                onclick="closeDeleteModal()"
            >
                Tidak
            </button>


            {{-- HAPUS --}}

            <form
                id="deleteModalForm"
                method="POST"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="confirm-delete"
                >
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>

@endsection


{{-- =====================================================
    CSS
===================================================== --}}

@push('styles')

<style>

/* =====================================================
   PAGE
===================================================== */

.page-section {

    width: 100%;

    max-width: 1500px;

    margin: 0 auto;

    padding: 85px 44px 45px;

    box-sizing: border-box;

}


/* =====================================================
   HEADER
===================================================== */

.page-top {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    gap: 30px;

    margin-bottom: 28px;

}


.page-heading {

    flex: 1;

    min-width: 0;

}


.back-link {

    display: inline-block;

    margin-bottom: 14px;

    color: #64748b;

    text-decoration: none;

    font-size: 13px;

    font-weight: 500;

    transition: 0.2s;

}


.back-link:hover {

    color: #2563eb;

}


.page-eyebrow {

    display: block;

    margin-bottom: 6px;

    color: #2563eb;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: 0.08em;

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

    line-height: 1.5;

}


.page-heading p strong {

    color: #0f172a;

}


/* =====================================================
   BUTTON TAMBAH
===================================================== */

.page-action {

    flex-shrink: 0;

}


.add-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    padding: 12px 18px;

    background: #2563eb;

    color: #ffffff;

    text-decoration: none;

    border-radius: 8px;

    font-size: 14px;

    font-weight: 600;

    transition: 0.2s;

}


.add-button:hover {

    background: #1d4ed8;

}


.add-button span {

    font-size: 20px;

    line-height: 1;

}


/* =====================================================
   INFORMASI DATA
===================================================== */

.data-info {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 17px 22px;

    margin-bottom: 20px;

    background: #ffffff;

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


.info-left span {

    color: #64748b;

    font-size: 13px;

}


.info-right {

    color: #64748b;

    font-size: 13px;

}


.info-right strong {

    color: #0f172a;

}


.info-right span {

    color: #94a3b8;

}


/* =====================================================
   TABLE CARD
===================================================== */

.table-card {

    background: #ffffff;

    border: 1px solid #e2e8f0;

    border-radius: 10px;

    overflow: hidden;

}


/* =====================================================
   TABLE HEADER
===================================================== */

.table-header {

    padding: 20px 22px;

    border-bottom: 1px solid #e2e8f0;

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

}


/* =====================================================
   TABLE
===================================================== */

.table-wrapper {

    width: 100%;

    overflow-x: auto;

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

    padding: 14px 18px;

    color: #ffffff;

    text-align: left;

    font-size: 13px;

    font-weight: 600;

    white-space: nowrap;

}


td {

    padding: 16px 18px;

    border-top: 1px solid #f1f5f9;

    color: #475569;

    font-size: 14px;

    vertical-align: middle;

}


tbody tr {

    transition: background 0.15s;

}


tbody tr:hover {

    background: #f8fafc;

}


/* =====================================================
   ID
===================================================== */

.kode-badge {

    color: #64748b;

    font-size: 13px;

    font-weight: 600;

    white-space: nowrap;

}


/* =====================================================
   NAMA SUB KATEGORI
===================================================== */

.sub-kategori-name {

    color: #07549f;

    font-size: 14px;

    font-weight: 600;

}


/* =====================================================
   DESKRIPSI
===================================================== */

.deskripsi-text {

    color: #64748b;

    font-size: 14px;

}


/* =====================================================
   AKSI
===================================================== */

.action-buttons {

    display: flex;

    align-items: center;

    gap: 8px;

}


/* =====================================================
   EDIT
===================================================== */

.edit-button {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #fff3c4;

    color: #9a6b00;

    border-radius: 7px;

    text-decoration: none;

    font-size: 17px;

    transition: 0.2s;

}


.edit-button:hover {

    background: #fde68a;

    transform: translateY(-1px);

}


/* =====================================================
   HAPUS
===================================================== */

.delete-button {

    min-width: 80px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 0 12px;

    border: none;

    background: #fee2e2;

    color: #dc2626;

    border-radius: 7px;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;

}


.delete-button:hover {

    background: #fecaca;

    transform: translateY(-1px);

}


.delete-button span {

    font-size: 13px;

}


/* =====================================================
   EMPTY DATA
===================================================== */

.empty-data {

    padding: 45px 20px !important;

    color: #94a3b8;

    text-align: center;

    font-size: 14px;

}


/* =====================================================
   ALERT SUCCESS
===================================================== */

.alert-success {

    width: calc(100% - 88px);

    max-width: 1412px;

    margin: 18px auto 0;

    padding: 12px 18px;

    background: #f0fdf4;

    border: 1px solid #bbf7d0;

    border-radius: 8px;

    color: #166534;

    font-size: 14px;

    box-sizing: border-box;

}


/* =====================================================
   MODAL
===================================================== */

.delete-modal {

    display: none;

    position: fixed;

    inset: 0;

    z-index: 9999;

    align-items: center;

    justify-content: center;

    padding: 20px;

    background: rgba(15, 23, 42, 0.45);

    box-sizing: border-box;

}


.delete-modal.show {

    display: flex;

}


.delete-modal-content {

    width: 100%;

    max-width: 420px;

    padding: 30px;

    background: #ffffff;

    border-radius: 12px;

    text-align: center;

    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);

    box-sizing: border-box;

}


.delete-icon {

    width: 52px;

    height: 52px;

    margin: 0 auto 16px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #fee2e2;

    color: #dc2626;

    border-radius: 50%;

    font-size: 22px;

}


.delete-modal-content h3 {

    margin: 0 0 8px;

    color: #0f172a;

    font-size: 20px;

    font-weight: 700;

}


.delete-modal-content p {

    margin: 0;

    color: #64748b;

    font-size: 14px;

    line-height: 1.6;

}


.delete-modal-content p strong {

    color: #0f172a;

}


/* =====================================================
   MODAL BUTTON
===================================================== */

.delete-modal-actions {

    display: flex;

    justify-content: center;

    gap: 10px;

    margin-top: 25px;

}


.cancel-delete {

    min-width: 100px;

    padding: 10px 18px;

    background: #ffffff;

    color: #475569;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    font-size: 14px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;

}


.cancel-delete:hover {

    background: #f8fafc;

}


.confirm-delete {

    min-width: 100px;

    padding: 10px 18px;

    background: #dc2626;

    color: #ffffff;

    border: 1px solid #dc2626;

    border-radius: 7px;

    font-size: 14px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;

}


.confirm-delete:hover {

    background: #b91c1c;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 900px) {

    .page-section {

        padding: 70px 20px 40px;

    }


    .page-top {

        flex-direction: column;

        align-items: flex-start;

    }


    .page-action {

        width: 100%;

    }


    .add-button {

        width: 100%;

    }


    .data-info {

        gap: 10px;

    }

}


@media (max-width: 600px) {

    .page-section {

        padding: 65px 15px 35px;

    }


    .data-info {

        flex-direction: column;

        align-items: flex-start;

        gap: 8px;

    }


    .info-right {

        font-size: 12px;

    }


    th,
    td {

        padding: 13px 12px;

    }


    .delete-modal-content {

        padding: 25px 20px;

    }


    .delete-modal-actions {

        width: 100%;

    }


    .cancel-delete,
    .confirm-delete {

        flex: 1;

    }

}

</style>

@endpush


{{-- =====================================================
    JAVASCRIPT
===================================================== --}}

@push('scripts')

<script>

function openDeleteModal(id, nama) {

    const modal =
        document.getElementById('deleteModal');

    const form =
        document.getElementById('deleteModalForm');

    const subCategoryName =
        document.getElementById('deleteSubCategoryName');


    if (!modal || !form || !subCategoryName) {

        return;

    }


    // Menampilkan nama sub kategori

    subCategoryName.textContent = nama;


    // URL hapus sub kategori

    form.action =
        '/kategori/{{ $kategori->id_kategori }}/sub-kategori/' + id;


    // Menampilkan modal

    modal.classList.add('show');

}


function closeDeleteModal() {

    const modal =
        document.getElementById('deleteModal');


    if (!modal) {

        return;

    }


    modal.classList.remove('show');

}


/* Klik area luar modal */

const deleteModal =
    document.getElementById('deleteModal');


if (deleteModal) {

    deleteModal.addEventListener(
        'click',
        function(event) {

            if (event.target === this) {

                closeDeleteModal();

            }

        }
    );

}


/* Tombol ESC */

document.addEventListener(
    'keydown',
    function(event) {

        if (event.key === 'Escape') {

            closeDeleteModal();

        }

    }
);

</script>

@endpush