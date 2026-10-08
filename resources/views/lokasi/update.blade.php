@extends('layouts.app')

@section('content')

<style>
    .update-container {
        padding: 90px 30px 30px;
        background: #f8fafc;
        min-height: 100vh;
    }

    .update-header {
        max-width: 750px;
        margin: 0 auto 25px;
    }

    .back-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .update-header h1 {
        margin: 12px 0 5px;
        color: #1e3a8a;
        font-size: 28px;
    }

    .update-header p {
        margin: 0;
        color: #64748b;
    }

    .form-card {
        max-width: 750px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .info-box {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        padding: 13px;
        margin-bottom: 25px;
        color: #92400e;
        font-size: 14px;
        border-radius: 5px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #334155;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .form-control:focus {
        border-color: #2563eb;
    }

    .readonly-control {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 140px;
    }

    .error-message {
        color: #dc2626;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #334155;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    .btn-update {
        background: #2563eb;
        color: white;
        border: none;
        padding: 11px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-update:hover {
        background: #1d4ed8;
    }

    @media (max-width: 768px) {
        .update-container {
            padding: 90px 15px 30px;
        }

        .form-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-update {
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
    }
</style>


<div class="update-container">

    {{-- HEADER --}}
    <div class="update-header">

        <a href="{{ route('lokasi.index') }}" class="back-link">
            ← Kembali ke Lokasi Aset
        </a>

        <h1>Update Lokasi Aset</h1>

        <p>
            Perbarui deskripsi lokasi aset
        </p>

    </div>


    {{-- FORM --}}
    <div class="form-card">

        <div class="info-box">
            ID dan nama lokasi merupakan identitas tetap sehingga tidak dapat diubah.
        </div>


        <form
            action="{{ route('lokasi.update', $lokasi->id_lokasi) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- ID LOKASI --}}
            <div class="form-group">

                <label for="id_lokasi">
                    ID Lokasi
                </label>

                <input
                    type="text"
                    id="id_lokasi"
                    class="form-control readonly-control"
                    value="{{ $lokasi->id_lokasi }}"
                    readonly
                >

            </div>


            {{-- NAMA LOKASI --}}
            <div class="form-group">

                <label for="nama_lokasi">
                    Nama Lokasi
                </label>

                <input
                    type="text"
                    id="nama_lokasi"
                    class="form-control readonly-control"
                    value="{{ $lokasi->nama_lokasi }}"
                    readonly
                >

            </div>


            {{-- DESKRIPSI --}}
            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    class="form-control"
                    placeholder="Masukkan deskripsi lokasi..."
                >{{ old('deskripsi', $lokasi->deskripsi) }}</textarea>

                @error('deskripsi')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- BUTTON --}}
            <div class="form-actions">

                <a
                    href="{{ route('lokasi.index') }}"
                    class="btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-update"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection