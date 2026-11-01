
@extends('layouts.app')

@section('content')

<div class="create-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('aset.index') }}" class="back-button">
                ←
            </a>

            <div>
                <span class="page-eyebrow">DATA ASET</span>

                <h1>Update Unit Aset</h1>

                <p>
                    Perbarui informasi unit aset yang telah terdaftar.
                </p>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            Terdapat data yang belum sesuai. Silakan periksa kembali form.
        </div>
    @endif

    {{-- FORM --}}
    <div class="form-card">

        <form
            action="{{ route('aset.update', $aset->kode_aset) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            {{-- KODE ASET --}}
            <div class="form-group">
                <label for="kode_aset">Kode Aset</label>

                <input
                    type="text"
                    id="kode_aset"
                    value="{{ $aset->kode_aset }}"
                    readonly
                >

                <small>
                    Kode aset merupakan identitas unik setiap unit dan tidak dapat diubah.
                </small>
            </div>

            {{-- JENIS ASET --}}
            <div class="form-group">
                <label for="nama_jenis">Jenis Aset</label>

                <input
                    type="text"
                    id="nama_jenis"
                    value="{{ $aset->jenis->nama_jenis ?? '-' }}"
                    readonly
                >

                <small>
                    Jenis aset mengikuti data jenis aset yang terdaftar.
                </small>
            </div>

            {{-- NAMA ASET --}}
            <div class="form-group">
                <label for="nama_aset">Nama Aset</label>

                <input
                    type="text"
                    id="nama_aset"
                    value="{{ $aset->nama_aset }}"
                    readonly
                >
            </div>

            {{-- LOKASI ASET --}}
            <div class="form-group">
                <label for="id_lokasi">
                    Lokasi Aset <span>*</span>
                </label>

                <select
                    id="id_lokasi"
                    name="id_lokasi"
                    required
                >
                    <option value="">Pilih Lokasi Aset</option>

                    @foreach ($lokasi as $item)
                        <option
                            value="{{ $item->id_lokasi }}"
                            {{ old('id_lokasi', $aset->id_lokasi) == $item->id_lokasi ? 'selected' : '' }}
                        >
                            {{ $item->nama_lokasi }}
                        </option>
                    @endforeach
                </select>

                @error('id_lokasi')
                    <small class="input-error">{{ $message }}</small>
                @else
                    <small>
                        Pilih lokasi tempat unit aset disimpan atau digunakan.
                    </small>
                @enderror
            </div>

            {{-- TANGGAL BELI --}}
            <div class="form-group">
                <label for="tanggal_beli">Tanggal Beli</label>

                <input
                    type="date"
                    id="tanggal_beli"
                    name="tanggal_beli"
                    value="{{ old('tanggal_beli', $aset->tanggal_beli ? $aset->tanggal_beli->format('Y-m-d') : '') }}"
                >

                @error('tanggal_beli')
                    <small class="input-error">{{ $message }}</small>
                @else
                    <small>
                        Isi tanggal pembelian aset jika tersedia. Kolom ini boleh dikosongkan.
                    </small>
                @enderror
            </div>

            {{-- KONDISI ASET --}}
            <div class="form-group">
                <label for="kondisi_aset">
                    Kondisi Aset <span>*</span>
                </label>

                <select
                    id="kondisi_aset"
                    name="kondisi_aset"
                    required
                    {{ $aset->status_aset === 'Dipinjam' ? 'disabled' : '' }}
                >
                    @foreach ($kondisiOptions as $kondisi)
                        <option
                            value="{{ $kondisi }}"
                            {{ old('kondisi_aset', $aset->kondisi_aset) === $kondisi ? 'selected' : '' }}
                        >
                            {{ $kondisi }}
                        </option>
                    @endforeach
                </select>

                @if ($aset->status_aset === 'Dipinjam')
                    <small class="field-warning">
                        Kondisi tidak dapat diubah karena aset sedang dipinjam. Kondisi akan diperbarui saat proses pengembalian.
                    </small>
                @else
                    @error('kondisi_aset')
                        <small class="input-error">{{ $message }}</small>
                    @else
                        <small>
                            Kondisi aset dapat diperbarui secara manual berdasarkan hasil pemeriksaan.
                        </small>
                    @enderror
                @endif
            </div>

            {{-- STATUS ASET --}}
            <div class="form-group">
                <label for="status_aset">
                    Status Aset <span>*</span>
                </label>

                <select
                    id="status_aset"
                    name="status_aset"
                    required
                    {{ $aset->status_aset === 'Dipinjam' ? 'disabled' : '' }}
                >
                    @if ($aset->status_aset === 'Dipinjam')
                        <option value="Dipinjam" selected>
                            Dipinjam
                        </option>
                    @endif

                    @foreach ($statusOptions as $status)
                        <option
                            value="{{ $status }}"
                            {{ old('status_aset', $aset->status_aset) === $status ? 'selected' : '' }}
                        >
                            {{ $status }}
                        </option>
                    @endforeach
                </select>

                @if ($aset->status_aset === 'Dipinjam')
                    <small class="field-warning">
                        Status tidak dapat diubah secara manual selama aset dipinjam. Status akan diperbarui melalui proses pengembalian.
                    </small>
                @else
                    @error('status_aset')
                        <small class="input-error">{{ $message }}</small>
                    @else
                        <small>
                            Pilih Tersedia untuk aset berkondisi Baik, Perlu Perbaikan untuk aset rusak, atau Nonaktif jika aset tidak digunakan.
                        </small>
                    @enderror
                @endif
            </div>

            {{-- GAMBAR ASET --}}
            <div class="form-group">
                <label for="gambar">Gambar Aset</label>

                @if ($aset->gambar)
                    <div class="current-image">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($aset->gambar) }}"
                            alt="Gambar {{ $aset->nama_aset }}"
                        >

                        <small>Gambar aset saat ini</small>
                    </div>
                @endif

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                @error('gambar')
                    <small class="input-error">{{ $message }}</small>
                @else
                    <small>
                        Format yang diperbolehkan JPG, JPEG, PNG, atau WEBP dengan ukuran maksimal 2 MB. Kosongkan jika tidak ingin mengganti gambar.
                    </small>
                @enderror
            </div>

            {{-- KETERANGAN --}}
            <div class="form-group">
                <label for="keterangan">Keterangan</label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="5"
                    placeholder="Masukkan keterangan tambahan mengenai aset..."
                >{{ old('keterangan', $aset->keterangan) }}</textarea>

                @error('keterangan')
                    <small class="input-error">{{ $message }}</small>
                @else
                    <small>
                        Tambahkan informasi lain mengenai unit aset jika diperlukan.
                    </small>
                @enderror
            </div>

            {{-- CATATAN --}}
            <div class="form-note">
                <strong>Catatan:</strong>

                Admin dapat memperbarui lokasi, tanggal beli, kondisi,
                status, gambar, dan keterangan aset. Namun, kondisi dan
                status aset yang sedang dipinjam hanya dapat diperbarui
                melalui proses pengembalian. Status Dipinjam juga hanya
                ditetapkan melalui proses peminjaman.
            </div>

            {{-- BUTTON --}}
            <div class="form-actions">
                <a
                    href="{{ route('aset.index') }}"
                    class="cancel-button"
                >
                    Batal
                </a>

                <button type="submit" class="save-button">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection

@push('styles')
<style>

/* PAGE */
.create-page {
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

/* HEADER TEXT */
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

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* FORM CARD */
.form-card {
    width: 100%;
    max-width: 850px;
    margin: 0 auto;
    background: white;
    padding: 28px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-sizing: border-box;
}

/* FORM GROUP */
.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #334155;
    font-size: 14px;
    font-weight: 600;
}

.form-group label span {
    color: #dc2626;
}

/* INPUT */
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    color: #334155;
    background: #ffffff;
}

.form-group input,
.form-group select {
    height: 42px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px #dbeafe;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #94a3b8;
}

.form-group input[readonly],
.form-group select:disabled {
    background: #f8fafc;
    color: #64748b;
    cursor: not-allowed;
    opacity: 1;
}

.form-group input[type="file"] {
    height: auto;
    padding: 9px;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

/* HELP TEXT */
.form-group small {
    display: block;
    margin-top: 6px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

/* ERROR */
.input-error {
    display: block;
    margin-top: 6px;
    color: #dc2626 !important;
    font-size: 12px !important;
}

/* WARNING */
.field-warning {
    color: #b45309 !important;
}

/* CURRENT IMAGE */
.current-image {
    margin-bottom: 12px;
}

.current-image img {
    display: block;
    width: 180px;
    max-width: 100%;
    max-height: 180px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.current-image small {
    margin-top: 7px;
}

/* NOTE */
.form-note {
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
.save-button {
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

.save-button {
    border: none;
    background: #2563eb;
    color: white;
}

.save-button:hover {
    background: #1d4ed8;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .create-page {
        padding: 100px 20px 40px;
    }

    .form-card {
        padding: 20px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-button,
    .save-button {
        width: 100%;
        text-align: center;
        box-sizing: border-box;
    }
}

</style>
@endpush
