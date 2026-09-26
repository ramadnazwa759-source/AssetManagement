<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kategori Aset - Kalisawah</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #1e293b;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #0b4f9c;
            color: white;
            padding: 25px 18px;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h2 {
            font-size: 22px;
        }

        .logo span {
            color: #ffd43b;
        }

        .menu-title {
            font-size: 12px;
            color: #cbd5e1;
            margin: 20px 10px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .menu a:hover,
        .menu a.active {
            background: #ffd43b;
            color: #0b4f9c;
            font-weight: bold;
        }

        /* MAIN */
        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            height: 70px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            border-bottom: 1px solid #e2e8f0;
        }

        .topbar h3 {
            color: #0b4f9c;
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .admin-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffd43b;
            color: #0b4f9c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* CONTENT */
        .content {
            padding: 30px 35px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            color: #0b4f9c;
            font-size: 25px;
        }

        .page-header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 5px;
        }

        /* BUTTON */
        .btn {
            border: none;
            padding: 11px 17px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .btn-yellow {
            background: #ffd43b;
            color: #0b4f9c;
        }

        .btn-blue {
            background: #0b4f9c;
            color: white;
        }

        .btn-red {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #475569;
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0b4f9c;
            color: white;
            text-align: left;
            padding: 15px;
            font-size: 13px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8fafc;
        }

        .id {
            color: #64748b;
            font-weight: bold;
        }

        .category-name {
            font-weight: bold;
            color: #0b4f9c;
        }

        .description {
            color: #64748b;
        }

        .actions {
            display: flex;
            gap: 7px;
        }

        .action-btn {
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .detail-btn {
            background: #e0efff;
            color: #0b4f9c;
        }

        .edit-btn {
            background: #fff3bf;
            color: #856404;
        }

        .delete-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        /* SUB CATEGORY */
        .subcategory-row {
            display: none;
            background: #f8fafc;
        }

        .subcategory-content {
            padding: 20px 30px 25px 75px;
        }

        .subcategory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .subcategory-header h4 {
            color: #0b4f9c;
            font-size: 15px;
        }

        .subcategory-table {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .subcategory-table th {
            background: #eaf3ff;
            color: #0b4f9c;
        }

        .subcategory-table td {
            padding: 11px 13px;
            font-size: 13px;
        }

        .small-btn {
            border: none;
            padding: 7px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .add-sub-btn {
            background: #ffd43b;
            color: #0b4f9c;
        }

        /* ALERT */
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        /* EMPTY */
        .empty {
            text-align: center;
            color: #64748b;
            padding: 30px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
            }

            .content {
                padding: 25px 20px;
            }

            .subcategory-content {
                padding-left: 30px;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 750px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <h2>KALI<span>SAWAH</span></h2>
        </div>

        <div class="menu-title">Menu</div>

        <div class="menu">
            <a href="{{ route('dashboard') }}">Dashboard</a>

            <a href="{{ route('kategori.index') }}" class="active">
                Kategori Aset
            </a>

            <a href="#">Sub Kategori</a>
            <a href="#">Jenis Aset</a>
            <a href="#">Lokasi Aset</a>
            <a href="#">Data Aset</a>
            <a href="#">Peminjaman Aset</a>
            <a href="#">Pengembalian Aset</a>
        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <h3>Asset Management</h3>

            <div class="admin">
                <div class="admin-icon">A</div>
                <span>Admin</span>
            </div>

        </header>


        <!-- CONTENT -->
        <section class="content">

            <!-- SUCCESS MESSAGE -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <!-- PAGE HEADER -->
            <div class="page-header">

                <div>
                    <h1>Kategori Aset</h1>
                    <p>Kelola kategori dan sub kategori aset Kalisawah.</p>
                </div>

                <a
                    href="{{ route('kategori.create') }}"
                    class="btn btn-yellow">
                    + Tambah Kategori
                </a>

            </div>


            <!-- CATEGORY TABLE -->
            <div class="table-container">

                <table>

                    <thead>
                        <tr>
                            <th width="150">ID Kategori</th>
                            <th width="220">Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="170">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($kategori as $item)

                            <!-- KATEGORI -->
                            <tr>

                                <td class="id">
                                    {{ $item->id_kategori }}
                                </td>

                                <td class="category-name">
                                    {{ $item->nama_kategori }}
                                </td>

                                <td class="description">
                                    {{ $item->Deskripsi ?? '-' }}
                                </td>

                                <td>

                                    <div class="actions">

                                        <!-- LIHAT SUB KATEGORI -->
                                        <button
                                            type="button"
                                            class="action-btn detail-btn"
                                            onclick="toggleSubcategory('sub-{{ $item->id_kategori }}', this)"
                                            title="Lihat Sub Kategori">
                                            ▼
                                        </button>

                                        <!-- EDIT -->
                                        <a
                                            href="{{ route('kategori.edit', $item->id_kategori) }}"
                                            class="action-btn edit-btn"
                                            title="Ubah"
                                            style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                            ✏
                                        </a>

                                        <!-- HAPUS -->
                                        <form
                                            action="{{ route('kategori.destroy', $item->id_kategori) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus kategori ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                                title="Hapus">
                                                🗑
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            <!-- SUB KATEGORI -->
                            <tr
                                id="sub-{{ $item->id_kategori }}"
                                class="subcategory-row">

                                <td colspan="4">

                                    <div class="subcategory-content">

                                        <div class="subcategory-header">

                                            <h4>
                                                Sub Kategori dari
                                                {{ $item->nama_kategori }}
                                            </h4>

                                            <form
                                                action="{{ route('sub-kategori.store') }}"
                                                method="POST"
                                                enctype="multipart/form-data">

                                                @csrf

                                                <input
                                                    type="hidden"
                                                    name="id_kategori"
                                                    value="{{ $item->id_kategori }}">

                                                <button
                                                    type="button"
                                                    class="small-btn add-sub-btn"
                                                    onclick="openSubCategoryForm('sub-form-{{ $item->id_kategori }}')">
                                                    + Tambah Sub Kategori
                                                </button>

                                            </form>

                                        </div>


                                        <!-- FORM TAMBAH SUBKATEGORI -->
                                        <div
                                            id="sub-form-{{ $item->id_kategori }}"
                                            style="display:none; background:white; padding:20px; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:15px;">

                                            <form
                                                action="{{ route('sub-kategori.store') }}"
                                                method="POST"
                                                enctype="multipart/form-data">

                                                @csrf

                                                <input
                                                    type="hidden"
                                                    name="id_kategori"
                                                    value="{{ $item->id_kategori }}">

                                                <div style="margin-bottom:12px;">
                                                    <label>Nama Sub Kategori</label>

                                                    <input
                                                        type="text"
                                                        name="nama_sub_kategori"
                                                        required
                                                        style="width:100%;padding:10px;margin-top:5px;">
                                                </div>

                                                <div style="margin-bottom:12px;">
                                                    <label>Gambar</label>

                                                    <input
                                                        type="file"
                                                        name="gambar"
                                                        accept="image/*"
                                                        style="width:100%;padding:8px;margin-top:5px;">
                                                </div>

                                                <div style="margin-bottom:12px;">
                                                    <label>Deskripsi</label>

                                                    <textarea
                                                        name="deskripsi"
                                                        rows="3"
                                                        style="width:100%;padding:10px;margin-top:5px;"></textarea>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="btn btn-blue">
                                                    Simpan
                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn btn-cancel"
                                                    onclick="openSubCategoryForm('sub-form-{{ $item->id_kategori }}')">
                                                    Batal
                                                </button>

                                            </form>

                                        </div>


                                        <!-- TABLE SUB KATEGORI -->
                                        <div class="subcategory-table">

                                            <table>

                                                <thead>
                                                    <tr>
                                                        <th>ID Sub Kategori</th>
                                                        <th>Nama Sub Kategori</th>
                                                        <th>Gambar</th>
                                                        <th>Deskripsi</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @php
                                                        $subItems = $subKategori->where(
                                                            'id_kategori',
                                                            $item->id_kategori
                                                        );
                                                    @endphp

                                                    @forelse($subItems as $sub)

                                                        <tr>

                                                            <td>
                                                                {{ $sub->id_sub_kategori }}
                                                            </td>

                                                            <td>
                                                                {{ $sub->nama_sub_kategori }}
                                                            </td>

                                                            <td>

                                                                @if($sub->gambar)
                                                                    <img
                                                                        src="{{ asset('storage/' . $sub->gambar) }}"
                                                                        width="60"
                                                                        height="45"
                                                                        style="object-fit:cover;border-radius:5px;">
                                                                @else
                                                                    -
                                                                @endif

                                                            </td>

                                                            <td>
                                                                {{ $sub->deskripsi ?? '-' }}
                                                            </td>

                                                            <td>

                                                                <div class="actions">

                                                                    <!-- EDIT SUB KATEGORI -->
                                                                    <button
                                                                        type="button"
                                                                        class="action-btn edit-btn"
                                                                        onclick="editSubCategory('{{ $sub->id_sub_kategori }}')">
                                                                        ✏
                                                                    </button>

                                                                    <!-- HAPUS SUB KATEGORI -->
                                                                    <form
                                                                        action="{{ route('sub-kategori.destroy', $sub->id_sub_kategori) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Hapus sub kategori ini?')">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button
                                                                            type="submit"
                                                                            class="action-btn delete-btn">
                                                                            🗑
                                                                        </button>

                                                                    </form>

                                                                </div>

                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>
                                                            <td colspan="5" class="empty">
                                                                Belum ada sub kategori.
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    Belum ada kategori aset.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <script>

        function toggleSubcategory(id, button) {

            const row = document.getElementById(id);

            if (row.style.display === "table-row") {

                row.style.display = "none";
                button.innerHTML = "▼";

            } else {

                row.style.display = "table-row";
                button.innerHTML = "▲";

            }

        }


        function openSubCategoryForm(id) {

            const form = document.getElementById(id);

            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }

        }


        function editSubCategory(id) {

            alert(
                'Edit sub kategori dengan ID: ' + id +
                '\n\nForm edit sub kategori belum tersedia pada controller.'
            );

        }

    </script>

</body>
</html>