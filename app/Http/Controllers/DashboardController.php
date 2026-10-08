<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\SubKategoriAset;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->get('asset_management_authenticated')) {
            return redirect()->route('auth.login');
        }

        $userId = $request->session()->get('asset_management_user_id');

        $user = User::where('id', $userId)->first();

        if (!$user || $user->role !== 'admin') {
            $request->session()->forget([
                'asset_management_authenticated',
                'asset_management_user_id',
            ]);

            return redirect()->route('auth.login');
        }

        /*
        |--------------------------------------------------------------------------
        | SUBKATEGORI UNTUK DASHBOARD
        |--------------------------------------------------------------------------
        */

        $subKategori = SubKategoriAset::with('jenisAset')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN ASET
        |--------------------------------------------------------------------------
        */

        $totalAset = Aset::count();

        $asetTersedia = Aset::where(
            'status_aset',
            'Tersedia'
        )->count();

        $asetDipinjam = Aset::where(
            'status_aset',
            'Dipinjam'
        )->count();

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