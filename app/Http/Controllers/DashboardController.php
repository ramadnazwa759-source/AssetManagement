<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\SubKategoriAset;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Asset Management
     */
    public function index(Request $request)
    {
        /*
         * VR-AM-DASH-001
         *
         * Dashboard hanya dapat diakses jika
         * Admin sudah memiliki autentikasi Asset Management.
         */
        if (!$request->session()->get('asset_management_authenticated')) {
            return redirect()->route('auth.login');
        }

        /*
         * Mengambil ID Admin yang sedang login
         * berdasarkan session Asset Management.
         */
        $userId = $request->session()->get(
            'asset_management_user_id'
        );

        /*
         * Tabel users menggunakan primary key "id".
         */
        $user = User::where('id', $userId)->first();

        /*
         * VR-AM-DASH-002
         *
         * Dashboard hanya dapat diakses oleh Admin.
         */
        if (!$user || $user->role !== 'admin') {
            $request->session()->forget([
                'asset_management_authenticated',
                'asset_management_user_id',
            ]);

            return redirect()->route('auth.login');
        }

        /*
         * BR-AM-DASH-003
         * VR-AM-DASH-005
         *
         * Data Dashboard diambil dari database
         * Asset Management.
         */

        // Data Subkategori untuk kebutuhan tampilan Dashboard
        $subKategori = SubKategoriAset::all();

        // Jumlah seluruh Unit Aset
        $totalAset = Aset::count();

        // Jumlah Unit Aset yang tersedia
        $asetTersedia = Aset::where(
            'status_aset',
            'Tersedia'
        )->count();

        // Jumlah Unit Aset yang sedang dipinjam
        $asetDipinjam = Aset::where(
            'status_aset',
            'Dipinjam'
        )->count();

        // Jumlah Unit Aset yang membutuhkan perbaikan
        $perluPerbaikan = Aset::where(
            'status_aset',
            'Perlu Perbaikan'
        )->count();

        return view(
            'dashboard.index',
            compact(
                'subKategori',
                'totalAset',
                'asetTersedia',
                'asetDipinjam',
                'perluPerbaikan'
            )
        );
    }
}