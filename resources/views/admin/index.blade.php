@extends('layouts.admin')

@section('title')
    Admin - Dashboard
@endsection

@section('content')
    <div class="w-full p-4 h-full">
        <div class="bg-white w-full h-full rounded-3xl overflow-auto px-10 py-8">
            <h1 class="font-bold text-2xl mb-6">Admin Dashboard</h1>
            <div class="flex flex-wrap gap-6">
                <!-- Card for Total Users -->
                <a href="{{ route('dashboard.users') }}" class="flex-1 min-w-[280px] bg-blue-500 text-white rounded-lg shadow-lg p-6 flex items-center transition-transform transform hover:scale-105">
                    <div class="text-center flex-1">
                        <h2 class="text-3xl font-bold">{{ $userCount }}</h2>
                        <p class="mt-2 text-sm">Jumlah Data User</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </a>

                <!-- Card for Total Reports Today -->
                <a href="{{ route('admin.laporan.index') }}" class="flex-1 min-w-[280px] bg-green-500 text-white rounded-lg shadow-lg p-6 flex items-center transition-transform transform hover:scale-105">
                    <div class="text-center flex-1">
                        <h2 class="text-3xl font-bold">{{ $todayLaporans }}</h2>
                        <p class="mt-2 text-sm">Jumlah Data Laporan Hari Ini</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <i class="fas fa-calendar-day fa-3x"></i>
                    </div>
                </a>

                <!-- Card for Unvalidated Reports Today -->
                <a href="{{ route('admin.laporan.index') }}" class="flex-1 min-w-[280px] bg-yellow-500 text-white rounded-lg shadow-lg p-6 flex items-center transition-transform transform hover:scale-105">
                    <div class="text-center flex-1">
                        <h2 class="text-3xl font-bold">{{ $todayUnvalidatedLaporans }}</h2>
                        <p class="mt-2 text-sm">Jumlah Data Laporan Belum Tervalidasi</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <i class="fas fa-hourglass-half fa-3x"></i>
                    </div>
                </a>

                <!-- Card for Validated Reports Today -->
                <a href="{{ route('admin.laporan.index') }}" class="flex-1 min-w-[280px] bg-green-600 text-white rounded-lg shadow-lg p-6 flex items-center transition-transform transform hover:scale-105">
                    <div class="text-center flex-1">
                        <h2 class="text-3xl font-bold">{{ $todayValidatedLaporans }}</h2>
                        <p class="mt-2 text-sm">Jumlah Data Laporan Tervalidasi</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </a>

                <!-- Card for Rejected Reports Today -->
                <a href="{{ route('admin.laporan.index') }}" class="flex-1 min-w-[280px] bg-red-500 text-white rounded-lg shadow-lg p-6 flex items-center transition-transform transform hover:scale-105">
                    <div class="text-center flex-1">
                        <h2 class="text-3xl font-bold">{{ $todayRejectedLaporans }}</h2>
                        <p class="mt-2 text-sm">Jumlah Data Laporan Ditolak</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <i class="fas fa-times-circle fa-3x"></i>
                    </div>
                </a>
            </div>

             <!-- Map Container -->
             <h1 class="mt-8 mb-2 text-base font-bold">Sebaran Lokasi Pekerjaan Hari ini</h1>
            <div id="map" class="w-full overflow-auto" style="height: 500px; margin-bottom: 20px;"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize map
            var map = L.map('map').setView([-7.2078, 107.8890], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = [];

            function loadLaporanData() {
                var today = new Date().toISOString().split('T')[0];
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.laporan.getDataPerDate') }}', // Adjust the URL if necessary
                    data: { date: today },
                    success: function (response) {
                        // Clear existing markers
                        markers.forEach(function (marker) {
                            map.removeLayer(marker);
                        });
                        markers = [];

                        // Update map markers
                        response.laporans.forEach(function (report) {
                            var lat = parseFloat(report.latitude);
                            var lon = parseFloat(report.longitude);

                            var imageUrl = report.foto ? '{{ asset("storage") }}/' + report.foto : '';

                            var popupContent = `
                                <div class="w-full aspect-square mb-2" style="height: 100px;">
                                    <img src="${imageUrl}" class="w-full h-full object-cover" alt="">
                                </div>
                                <b>Nama Petugas:</b> ${report.created_by_user.name}<br>
                                <b>Desa:</b> ${report.village.name}<br>
                                <b>Kecamatan:</b> ${report.district.name}<br>
                                <b>Tanggal:</b> ${new Date(report.created_at).toLocaleString()}<br>
                                <b>Status:</b> ${report.status == 1 ? 'Tervalidasi' : report.status == 0 ? 'Belum Tervalidasi' : 'Ditolak'}<br>
                            `;

                            var marker = L.marker([lat, lon]).addTo(map);
                            marker.bindPopup(popupContent);
                            markers.push(marker);
                        });
                    }
                });
            }

            loadLaporanData();
        });
    </script>
@endsection
