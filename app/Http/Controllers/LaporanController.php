<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Laporan;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $dateFilter = $request->input('date', now()->format('Y-m-d'));

        $laporans = Laporan::with(['createdByUser', 'village', 'district'])
            ->whereDate('created_at', $dateFilter)
            ->latest()
            ->get();

        return view('admin.laporan.index', compact('authUser', 'laporans', 'dateFilter'));
    }


    public function getDatabyDate(Request $request)
    {
        $dateFilter = $request->input('date', now()->format('Y-m-d'));

        $laporans = Laporan::with(['createdByUser', 'village', 'district'])
            ->whereDate('created_at', $dateFilter)
            ->latest()
            ->get();

        return response()->json(['laporans' => $laporans]);
    }


    public function create()
    {
        $kecamatans = District::where('regency_id', 3205)->get();

        return view('petugas.laporan.create', compact('kecamatans'));
    }

    public function getDesa($kecamatanId)
    {
        $desas = Village::where('district_id', $kecamatanId)->get();
        return response()->json($desas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos', 'public');
        }

        Laporan::create([
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'foto' => $path,
            'keterangan' => $request->keterangan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    // Menampilkan form edit laporan
    public function edit($id)
    {
        $kecamatans = District::where('regency_id', 3205)->get();
        $laporan = Laporan::findOrFail($id);
        
        // Pastikan pengguna hanya dapat mengedit laporannya sendiri
        if ($laporan->created_by !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        return view('petugas.laporan.edit', compact('laporan', 'kecamatans'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Pastikan pengguna hanya dapat mengupdate laporannya sendiri
        if ($laporan->created_by !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $path = $laporan->foto; // Simpan path foto lama

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($laporan->foto && Storage::exists($laporan->foto)) {
                Storage::delete($laporan->foto);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('fotos', 'public');
        }

        $laporan->update([
            'desa' => $request->input('desa'),
            'kecamatan' => $request->input('kecamatan'),
            'foto' => $path,
            'keterangan' => $request->input('keterangan'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'status' => $request->input('status', 0),
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Laporan updated successfully.');
    }


    // Menghapus laporan
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Pastikan pengguna hanya dapat menghapus laporannya sendiri
        if ($laporan->created_by !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        if (Storage::exists($laporan->foto)) {
            Storage::delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('dashboard.index')->with('success', 'Laporan deleted successfully.');
    }

    public function validateReport(Request $request, Laporan $report)
    {
        // Validasi apakah user adalah admin
        $authUser = Auth::user();
        if ($authUser && $authUser->level == 1) {
            // Update status laporan menjadi valid
            $report->update(['status' => 1]);

            // Redirect kembali dengan pesan sukses
            return redirect()->route('admin.laporan.index')->with('success', 'Laporan telah berhasil divalidasi.');
        }

        // Jika user bukan admin, redirect ke halaman beranda
        return redirect('/')->with('error', 'Anda tidak memiliki akses untuk melakukan validasi laporan.');
    }

    public function rejectReport(Request $request, Laporan $report)
    {
        // Validasi apakah user adalah admin
        $authUser = Auth::user();
        if ($authUser && $authUser->level == 1) {
            // Update status laporan menjadi ditolak
            $report->update(['status' => 2]);

            // Redirect kembali dengan pesan sukses
            return redirect()->route('admin.laporan.index')->with('success', 'Laporan telah berhasil ditolak.');
        }

        // Jika user bukan admin, redirect ke halaman beranda
        return redirect('/')->with('error', 'Anda tidak memiliki akses untuk menolak laporan.');
    }

}
