
@extends('layouts.app')

@section('content')
<style>
    .pjm-page {
        min-height: 100vh;
        padding: 30px 0 45px;
        background: #f8fafc;
    }

    .pjm-container {
        width: min(1050px, calc(100% - 40px));
        margin: auto;
    }

    .pjm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
    }

    .pjm-heading small {
        display: block;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .7px;
        margin-bottom: 6px;
    }

    .pjm-heading h1 {
        margin: 0;
        color: #1e293b;
        font-size: 25px;
        font-weight: 700;
    }

    .pjm-heading p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .pjm-back {
        padding: 10px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .pjm-back:hover {
        background: #f1f5f9;
        color: #1d4ed8;
    }

    .pjm-card {
        padding: 25px 27px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .025);
    }

    .pjm-card-heading {
        padding-bottom: 17px;
        margin-bottom: 21px;
        border-bottom: 1px solid #edf0f4;
    }

    .pjm-card-heading h2 {
        margin: 0;
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
    }

    .pjm-card-heading p {
        margin: 5px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .pjm-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 22px;
    }

    .pjm-field {
        min-width: 0;
    }

    .pjm-field.full {
        grid-column: 1 / -1;
    }

    .pjm-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .pjm-required {
        color: #dc2626;
    }

    .pjm-input {
        display: block;
        width: 100%;
        height: 42px;
        padding: 9px 12px;
        border: 1px solid #d5dce5;
        border-radius: 7px;
        background: #fff;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .pjm-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .09);
    }

    textarea.pjm-input {
        height: 92px;
        resize: vertical;
        line-height: 1.5;
    }

    .pjm-hint {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .pjm-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 11px;
    }

    .pjm-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 12px;
    }

    /* Baris pilihan aset */
    .asset-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .asset-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 150px 38px;
        align-items: end;
        gap: 15px;
        padding: 17px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #fbfcfe;
    }

    .asset-field {
        min-width: 0;
    }

    .asset-field .pjm-input {
        height: 43px;
    }

    .asset-stock {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 11px;
    }

    .asset-remove {
        width: 38px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fecaca;
        border-radius: 7px;
        background: #fff;
        color: #dc2626;
        font-size: 18px;
        cursor: pointer;
    }

    .asset-remove:hover {
        background: #fef2f2;
    }

    .asset-remove:disabled {
        opacity: .35;
        cursor: not-allowed;
    }

    .asset-add {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 15px;
        padding: 9px 13px;
        border: 1px solid #bfdbfe;
        border-radius: 7px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .asset-add:hover {
        background: #dbeafe;
    }

    .pjm-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        margin-top: 18px;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #f5f9ff;
        color: #475569;
        font-size: 12px;
        line-height: 1.6;
    }

    .pjm-info strong {
        color: #1e40af;
    }

    .pjm-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .pjm-btn {
        min-width: 125px;
        height: 41px;
        padding: 0 17px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .pjm-btn-cancel {
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
    }

    .pjm-btn-submit {
        border: 1px solid #2563eb;
        background: #2563eb;
        color: #fff;
    }

    .pjm-btn-submit:hover {
        background: #1d4ed8;
    }

    @media (max-width: 650px) {
        .pjm-container {
            width: calc(100% - 24px);
        }

        .pjm-card {
            padding: 19px;
        }

        .pjm-grid {
            grid-template-columns: 1fr;
        }

        .pjm-field.full {
            grid-column: auto;
        }

        .asset-row {
            grid-template-columns: minmax(0, 1fr) 105px 34px;
            gap: 8px;
            padding: 12px;
        }

        .asset-remove {
            width: 34px;
        }

        .pjm-heading h1 {
            font-size: 21px;
        }

        .pjm-actions {
            flex-direction: column-reverse;
        }

        .pjm-btn {
            width: 100%;
        }
    }
</style>

<div class="pjm-page">
    <div class="pjm-container">

        <div class="pjm-header">
            <div class="pjm-heading">
                <small>PENGELOLAAN ASET</small>
                <h1>Tambah Peminjaman</h1>
                <p>Lengkapi informasi untuk mencatat transaksi peminjaman aset.</p>
            </div>

            <a href="{{ route('peminjaman.index') }}" class="pjm-back">
                ← Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="pjm-alert">
                <strong>Periksa kembali data yang dimasukkan.</strong>
                <ul style="margin: 7px 0 0; padding-left: 19px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf

            {{-- INFORMASI PEMINJAM --}}
            <section class="pjm-card">
                <div class="pjm-card-heading">
                    <h2>Informasi Peminjam</h2>
                    <p>Masukkan informasi pihak yang melakukan peminjaman.</p>
                </div>

                <div class="pjm-grid">
                    <div class="pjm-field full">
                        <label class="pjm-label" for="nama_peminjam">
                            Nama Peminjam <span class="pjm-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_peminjam"
                            name="nama_peminjam"
                            class="pjm-input"
                            value="{{ old('nama_peminjam') }}"
                            maxlength="100"
                            placeholder="Masukkan nama peminjam"
                            required
                        >
                        @error('nama_peminjam')
                            <span class="pjm-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pjm-field">
                        <label class="pjm-label" for="tanggal_pinjam">
                            Tanggal Peminjaman <span class="pjm-required">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_pinjam"
                            name="tanggal_pinjam"
                            class="pjm-input"
                            value="{{ old('tanggal_pinjam') }}"
                            required
                        >
                        @error('tanggal_pinjam')
                            <span class="pjm-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pjm-field">
                        <label class="pjm-label" for="tanggal_pengembalian">
                            Rencana Pengembalian <span class="pjm-required">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_pengembalian"
                            name="tanggal_pengembalian"
                            class="pjm-input"
                            value="{{ old('tanggal_pengembalian') }}"
                            required
                        >
                        @error('tanggal_pengembalian')
                            <span class="pjm-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pjm-field full">
                        <label class="pjm-label" for="tujuan">
                            Tujuan Peminjaman <span class="pjm-required">*</span>
                        </label>
                        <textarea
                            id="tujuan"
                            name="tujuan"
                            class="pjm-input"
                            maxlength="255"
                            placeholder="Tuliskan tujuan penggunaan aset"
                            required
                        >{{ old('tujuan') }}</textarea>
                        @error('tujuan')
                            <span class="pjm-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pjm-field full">
                        <label class="pjm-label" for="catatan">
                            Keterangan / Catatan
                        </label>
                        <textarea
                            id="catatan"
                            name="catatan"
                            class="pjm-input"
                            maxlength="1000"
                            placeholder="Tambahkan keterangan jika diperlukan"
                        >{{ old('catatan') }}</textarea>
                        <span class="pjm-hint">Opsional, maksimal 1.000 karakter.</span>
                        @error('catatan')
                            <span class="pjm-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- DETAIL ASET --}}
            <section class="pjm-card">
                <div class="pjm-card-heading">
                    <h2>Detail Aset yang Dipinjam</h2>
                    <p>Pilih jenis aset dan tentukan jumlah unit yang dibutuhkan.</p>
                </div>

                @php
                    $oldDetails = old('detail', [
                        ['id_jenis' => '', 'jumlah_pinjam' => 1]
                    ]);
                @endphp

                <div id="asset-list" class="asset-list">
                    @foreach ($oldDetails as $index => $oldDetail)
                        <div class="asset-row">
                            <div class="asset-field">
                                <label class="pjm-label">
                                    Jenis Aset <span class="pjm-required">*</span>
                                </label>

                                <select
                                    name="detail[{{ $index }}][id_jenis]"
                                    class="pjm-input jenis-select"
                                    required
                                >
                                    <option value="">Pilih jenis aset</option>

                                    @foreach ($jenisAset as $jenis)
                                        <option
                                            value="{{ $jenis->id_jenis }}"
                                            data-stok="{{ $jenis->stok_tersedia }}"
                                            @selected(($oldDetail['id_jenis'] ?? '') == $jenis->id_jenis)
                                        >
                                            {{ $jenis->nama_jenis }}
                                            ({{ $jenis->stok_tersedia }} tersedia)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="asset-field">
                                <label class="pjm-label">
                                    Jumlah Unit <span class="pjm-required">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="detail[{{ $index }}][jumlah_pinjam]"
                                    class="pjm-input jumlah-input"
                                    min="1"
                                    step="1"
                                    value="{{ $oldDetail['jumlah_pinjam'] ?? 1 }}"
                                    required
                                >

                                <span class="asset-stock">Minimal 1 unit.</span>
                            </div>

                            <button
                                type="button"
                                class="asset-remove"
                                title="Hapus jenis aset"
                                aria-label="Hapus jenis aset"
                            >×</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-asset" class="asset-add">
                    <span>＋</span> Tambah Jenis Aset
                </button>

                @error('detail')
                    <span class="pjm-error">{{ $message }}</span>
                @enderror

                <div class="pjm-info">
                    <span>ⓘ</span>
                    <div>
                        Unit aset akan dipilih otomatis oleh sistem berdasarkan
                        kondisi <strong>Baik</strong> dan status <strong>Tersedia</strong>.
                        Pastikan jumlah yang dimasukkan sesuai dengan stok yang tersedia.
                        Sistem akan memeriksa kembali ketersediaan saat transaksi disimpan.
                    </div>
                </div>
            </section>

            <div class="pjm-actions">
                <a href="{{ route('peminjaman.index') }}" class="pjm-btn pjm-btn-cancel">
                    Batal
                </a>

                <button type="submit" class="pjm-btn pjm-btn-submit">
                    Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('asset-list');
    const addButton = document.getElementById('add-asset');

    let nextIndex = {{ count($oldDetails) }};

    function updateRows() {
        const rows = Array.from(list.querySelectorAll('.asset-row'));

        const selectedIds = rows
            .map(row => row.querySelector('.jenis-select').value)
            .filter(value => value !== '');

        rows.forEach(row => {
            const select = row.querySelector('.jenis-select');
            const quantity = row.querySelector('.jumlah-input');
            const stockText = row.querySelector('.asset-stock');
            const option = select.options[select.selectedIndex];

            Array.from(select.options).forEach(item => {
                if (!item.value) return;

                item.disabled =
                    selectedIds.includes(item.value) &&
                    item.value !== select.value;
            });

            if (select.value) {
                const stock = Number(option.dataset.stok || 0);

                quantity.max = stock;
                stockText.textContent = `Stok tersedia: ${stock} unit.`;

                if (Number(quantity.value) > stock) {
                    quantity.setCustomValidity(
                        `Jumlah melebihi stok tersedia (${stock} unit).`
                    );
                } else {
                    quantity.setCustomValidity('');
                }
            } else {
                quantity.removeAttribute('max');
                quantity.setCustomValidity('');
                stockText.textContent = 'Pilih jenis aset terlebih dahulu.';
            }

            row.querySelector('.asset-remove').disabled = rows.length === 1;
        });
    }

    function addRow() {
        const firstSelect = list.querySelector('.jenis-select');
        const row = document.createElement('div');
        row.className = 'asset-row';

        const select = firstSelect.cloneNode(true);
        select.name = `detail[${nextIndex}][id_jenis]`;
        select.value = '';

        const selectField = document.createElement('div');
        selectField.className = 'asset-field';

        const selectLabel = document.createElement('label');
        selectLabel.className = 'pjm-label';
        selectLabel.innerHTML = 'Jenis Aset <span class="pjm-required">*</span>';

        selectField.append(selectLabel, select);

        const quantityField = document.createElement('div');
        quantityField.className = 'asset-field';

        const quantityLabel = document.createElement('label');
        quantityLabel.className = 'pjm-label';
        quantityLabel.innerHTML = 'Jumlah Unit <span class="pjm-required">*</span>';

        const quantity = document.createElement('input');
        quantity.type = 'number';
        quantity.name = `detail[${nextIndex}][jumlah_pinjam]`;
        quantity.className = 'pjm-input jumlah-input';
        quantity.min = '1';
        quantity.step = '1';
        quantity.value = '1';
        quantity.required = true;

        const hint = document.createElement('span');
        hint.className = 'asset-stock';
        hint.textContent = 'Pilih jenis aset terlebih dahulu.';

        quantityField.append(quantityLabel, quantity, hint);

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'asset-remove';
        remove.title = 'Hapus jenis aset';
        remove.setAttribute('aria-label', 'Hapus jenis aset');
        remove.textContent = '×';

        row.append(selectField, quantityField, remove);
        list.appendChild(row);

        nextIndex++;
        updateRows();
    }

    addButton.addEventListener('click', addRow);

    list.addEventListener('change', function (event) {
        if (event.target.classList.contains('jenis-select')) {
            updateRows();
        }
    });

    list.addEventListener('input', function (event) {
        if (event.target.classList.contains('jumlah-input')) {
            updateRows();
        }
    });

    list.addEventListener('click', function (event) {
        if (event.target.classList.contains('asset-remove')) {
            if (list.querySelectorAll('.asset-row').length > 1) {
                event.target.closest('.asset-row').remove();
                updateRows();
            }
        }
    });

    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_pengembalian');

    function updateTanggal() {
        tanggalKembali.min = tanggalPinjam.value || '';
    }

    tanggalPinjam.addEventListener('change', updateTanggal);
    updateTanggal();

    updateRows();
});
</script>
@endsection
