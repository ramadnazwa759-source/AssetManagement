```php
<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    // Menampilkan daftar aset
    public function index()
    {
        $aset = Aset::with([
            'jenis',
            'lokasi'
        ])
            ->latest()
            ->get();

        return view(
            'asset-management.aset.index',
            compact('aset')
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
        $aset = Aset::findOrFail($id);

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

        // Validasi data
        $validated = $request->validate([
            'id_lokasi' => 'nullable|exists:lokasi_aset,id_lokasi',

            'tanggal_beli' => 'nullable|date',

            'kondisi_aset' => 'required|in:Baik,Rusak Ringan,Rusak Berat',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'keterangan' => 'nullable|string',
        ]);

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if (
                $aset->gambar &&
                Storage::disk('public')->exists($aset->gambar)
            ) {
                Storage::disk('public')->delete($aset->gambar);
            }

            // Simpan gambar baru
            $validated['gambar'] = $request
                ->file('gambar')
                ->store('aset', 'public');
        }

        // Simpan perubahan
        $aset->update($validated);

        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Detail aset berhasil diperbarui.'
            );
    }
}
