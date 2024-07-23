@extends('layouts.admin')

@section('title')
    Admin - Manajemen Laporan
@endsection

@section('content')
    <div class="w-4/5 p-4 h-full">
        <div class="bg-white w-full h-full rounded-3xl overflow-auto px-10 py-8">
            <h1 class="font-bold text-2xl mb-6">Data Laporan</h1>
            @if(session('success'))
                <div class="bg-green-500 text-white p-2 rounded mb-4 mt-6">
                    {{ session('success') }}
                </div>
            @endif
            <div class="relative overflow-x-auto mt-6">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama User Pembuat
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Desa
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Kecamatan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Bukti Foto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cek Lokasi
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $index => $report)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $report->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $report->createdByUser->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $report->village->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $report->district->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->foto)
                                        <img src="{{ asset('storage/' . $report->foto) }}" class="w-20 h-20 object-cover rounded-sm" alt="">
                                        <a href="{{ asset('storage/' . $report->foto) }}" target="_blank" class="text-blue-500 underline">Lihat Foto</a>
                                    @else
                                        Tidak ada foto
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="text-blue-500 underline">Lihat Lokasi</a>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($report->status == 1)
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-lg text-xs">Tervalidasi</span>
                                    @elseif($report->status == 0)
                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded-lg text-xs">Belum Tervalidasi</span>
                                    @else
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-lg text-xs">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$report->status)
                                        <form action="{{ route('dashboard.reports.validate', $report) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-500 text-white p-2 rounded validate-report flex items-center">
                                                <span class="mr-1"><i class="fas fa-check"></i></span> Validasi
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.reports.reject', $report) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-500 text-white p-2 rounded reject-report flex items-center">
                                                <span class="mr-1"><i class="fa-solid fa-xmark"></i></span> Tolak
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-report');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus saja!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form untuk menghapus laporan
                            this.parentNode.submit();
                        }
                    });
                });
            });

            const validateButtons = document.querySelectorAll('.validate-report');
            validateButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Laporan ini akan divalidasi!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, validasi!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form untuk validasi laporan
                            this.parentNode.submit();
                        }
                    });
                });
            });

            const rejectButtons = document.querySelectorAll('.reject-report');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Laporan ini akan ditolak!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, tolak!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form untuk validasi laporan
                            this.parentNode.submit();
                        }
                    });
                });
            });

            // Menampilkan Sweet Alert untuk pesan success atau error
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        });
    </script>
@endsection
