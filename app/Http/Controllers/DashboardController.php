<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;
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

            $userCount = User::count();
            $today = Carbon::today();
            $todayLaporans = Laporan::whereDate('created_at', $today)->count();
            $todayUnvalidatedLaporans = Laporan::whereDate('created_at', $today)
                ->where('status', 0)
                ->count();
            $todayValidatedLaporans = Laporan::whereDate('created_at', $today)
                ->where('status', 1)
                ->count();
            $todayRejectedLaporans = Laporan::whereDate('created_at', $today)
                ->where('status', 2)
                ->count();

            return view('admin.index', compact('authUser', 'laporans', 'userCount', 'todayLaporans', 'todayUnvalidatedLaporans', 'todayValidatedLaporans', 'todayRejectedLaporans'));
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
