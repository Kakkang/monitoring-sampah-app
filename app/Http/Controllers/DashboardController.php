<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

        if ($authUser && $authUser->level == 1) {
            // Untuk Admin
            $laporans = Laporan::with(['createdByUser', 'village', 'district'])
                ->latest()
                ->get();
            return view('admin.index', compact('authUser', 'laporans'));
        } else if ($authUser && $authUser->level == 2) {
            // Untuk Petugas
            $laporans = Laporan::with(['village', 'district'])
                ->where('created_by', Auth::id())
                ->latest()
                ->get();

            return view('petugas.index', compact('authUser', 'laporans'));
        }

        return redirect('/'); 
    }
}
