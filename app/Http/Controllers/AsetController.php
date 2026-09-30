<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
     // Menampilkan daftar aset
    public function index(Request $request)
    {
        $query = Aset::with([
            'jenis',
            'lokasi'
        ]);

        // Mencari aset
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_aset', 'like', "%{$search}%")
                    ->orWhere('nama_aset', 'like', "%{$search}%")
                    ->orWhereHas('jenis', function ($jenis) use ($search) {
                        $jenis->where(
                            'nama_jenis',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        // Memfilter berdasarkan jenis aset
        if ($request->filled('id_jenis')) {
            $query->where(
                'id_jenis',
                $request->id_jenis
            );
        }

        // Memfilter berdasarkan lokasi
        if ($request->filled('id_lokasi')) {
            $query->where(
                'id_lokasi',
                $request->id_lokasi
            );
        }

        // Memfilter berdasarkan kondisi
        if ($request->filled('kondisi_aset')) {
            $query->where(
                'kondisi_aset',
                $request->kondisi_aset
            );
        }

        // Memfilter berdasarkan status
        if ($request->filled('status_aset')) {
            $query->where(
                'status_aset',
                $request->status_aset
            );
        }

        $aset = $query
            ->latest()
            ->get();

        $lokasi = LokasiAset::all();

        return view(
            'asset-management.aset.index',
            compact('aset', 'lokasi')
        );
    }

     // Menampilkan detail aset
    public function show(string $id)
    {
        $aset = Aset::with([
            'jenis',
            'lokasi'
        ])->findOrFail($id);

        return view(
            'asset-management.aset.show',
            compact('aset')
        );
    }

    // Menampilkan form edit aset
    public function edit(string $id)
    {
        $aset = Aset::with([
            'jenis',
            'lokasi'
        ])->findOrFail($id);

        $lokasi = LokasiAset::all();

        return view(
            'asset-management.aset.edit',
            compact('aset', 'lokasi')
        );
    }

      // Memperbarui detail aset
    public function update(Request $request, string $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'id_lokasi' =>
                'required|exists:lokasi_aset,id_lokasi',

            'tanggal_beli' =>
                'nullable|date',

            'kondisi_aset' =>
                'required|in:Baik,Rusak Ringan,Rusak Berat',

            'gambar' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'keterangan' =>
                'nullable|string',
        ]);

        // Mengganti gambar aset
        if ($request->hasFile('gambar')) {

            if (
                $aset->gambar &&
                Storage::disk('public')->exists($aset->gambar)
            ) {
                Storage::disk('public')->delete(
                    $aset->gambar
                );
            }

            $validated['gambar'] = $request
                ->file('gambar')
                ->store('aset', 'public');
        }

        $aset->update($validated);

        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Detail aset berhasil diperbarui.'
            );
    }
}
