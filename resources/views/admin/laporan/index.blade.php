@extends('layouts.admin')

@section('title')
    Admin - Manajemen Laporan
@endsection

@section('content')
    <div class="w-4/5 p-4 h-full">
        <div class="bg-white max-w-full h-full overflow-auto rounded-3xl px-10 py-8">
            <h1 class="font-bold text-2xl mb-6">Data Laporan</h1>
            @if(session('success'))
                <div class="bg-green-500 text-white p-2 rounded mb-4 mt-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Date Form -->
            <div class="mb-6">
                <label for="date" class="block mb-2 text-sm font-medium text-gray-700">Filter Tanggal</label>
                <input type="date" id="date" name="date" value="{{ $dateFilter }}" class="w-fit bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" />
            </div>

            <!-- Table Container -->
            <div class="relative overflow-x-auto mt-6">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Nama User Pembuat</th>
                            <th scope="col" class="px-6 py-3">Desa</th>
                            <th scope="col" class="px-6 py-3">Kecamatan</th>
                            <th scope="col" class="px-6 py-3">Bukti Foto</th>
                            <th scope="col" class="px-6 py-3">Cek Lokasi</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="laporan-tbody">
                        @foreach($laporans as $index => $report)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $report->createdByUser->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $report->village->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $report->district->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->foto)
                                        <img src="{{ asset('storage/' . $report->foto) }}" class="w-80 aspect-square object-cover rounded-sm cursor-pointer view-photo" data-foto="{{ asset('storage/' . $report->foto) }}" alt="">
                                    @else
                                        Tidak ada foto
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="bg-blue-500 px-2 py-1 text-white rounded-lg">Lihat Lokasi</a>
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
            <!-- Map Container -->
            <h1 class="mt-8 mb-2 text-lg font-bold">Sebaran Lokasi Pekerjaan</h1>
            <div id="map" class="w-full overflow-auto" style="height: 500px; margin-bottom: 20px;"></div>
        </div>

        <!-- Photo Modal Container -->
        <div id="photo-modal" class="fixed inset-0 z-[10000] hidden bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white w-11/12 md:w-1/2 lg:w-1/3 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center bg-blue-500 p-4 text-white">
                    <h2 class="text-lg font-bold">Foto Bukti</h2>
                    <button id="photo-modal-close" class="text-2xl font-bold">&times;</button>
                </div>
                <div id="photo-modal-content" class="p-4 flex justify-center items-center">
                    <img id="photo-modal-image" src="" alt="" class="max-w-full max-h-screen">
                </div>
            </div>
        </div>
    </div>

    <!-- Script Sweet Alert and Leaflet -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize map
            var map = L.map('map').setView([-7.2078, 107.8890], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = [];

            function loadLaporanData() {
                var tanggal = $('#date').val();
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.laporan.getDataPerDate') }}',
                    data: { date: tanggal },
                    success: function (response) {
                        // Clear existing markers
                        markers.forEach(function (marker) {
                            map.removeLayer(marker);
                        });
                        markers = [];

                        // Check if there are reports to display
                        if (response.laporans && response.laporans.length > 0) {
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

                            // Update table
                            var tbody = $('#laporan-tbody');
                            tbody.empty();

                            response.laporans.forEach(function (report, index) {
                                var statusText = report.status == 1 ? 'Tervalidasi' : report.status == 0 ? 'Belum Tervalidasi' : 'Ditolak';
                                var statusClass = report.status == 1 ? 'bg-green-500' : report.status == 0 ? 'bg-yellow-500' : 'bg-red-500';
                                var photoHtml = report.foto ? `<img src="{{ asset('storage/') }}/${report.foto}" class="w-80 aspect-square object-cover rounded-sm cursor-pointer view-photo" data-foto="{{ asset('storage/') }}/${report.foto}" alt="">` : 'Tidak ada foto';
                                var validateRejectButtons = !report.status ? `
                                    @isset($report) {{-- atau @if(isset($report)) --}}
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
                                    @endisset
                                    ` : '';
                                tbody.append(`
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${new Date(report.created_at).toLocaleString()}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${report.created_by_user.name}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${report.village.name}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${report.district.name}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${photoHtml}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="https://www.google.com/maps?q=${report.latitude},${report.longitude}" target="_blank" class="bg-blue-500 px-2 py-1 text-white rounded-lg">Lihat Lokasi</a>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span class="${statusClass} text-white px-2 py-1 rounded-lg text-xs">${statusText}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">${validateRejectButtons}</td>
                                    </tr>
                                `);
                            });

                            // Initialize event listeners for newly added photo elements
                            document.querySelectorAll('.view-photo').forEach(img => {
                                img.addEventListener('click', function () {
                                    const photoUrl = this.dataset.foto;
                                    document.getElementById('photo-modal-image').src = photoUrl;
                                    document.getElementById('photo-modal').classList.remove('hidden');
                                });
                            });

                            // SweetAlert setup
                            const handleButtonClick = (e, title, text, confirmButtonText, callback) => {
                                e.preventDefault();
                                Swal.fire({
                                    title: title,
                                    text: text,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: confirmButtonText,
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        callback();
                                    }
                                });
                            };

                            const validateButtons = document.querySelectorAll('.validate-report');
                            validateButtons.forEach(button => {
                                button.addEventListener('click', function (e) {
                                    handleButtonClick(e, 'Apakah Anda yakin?', 'Laporan ini akan divalidasi!', 'Ya, validasi!', () => {
                                        this.parentNode.submit();
                                    });
                                });
                            });

                            const rejectButtons = document.querySelectorAll('.reject-report');
                            rejectButtons.forEach(button => {
                                button.addEventListener('click', function (e) {
                                    handleButtonClick(e, 'Apakah Anda yakin?', 'Laporan ini akan ditolak!', 'Ya, tolak!', () => {
                                        this.parentNode.submit();
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
                        } else {
                            // Handle case when there are no reports
                            $('#laporan-tbody').html(`
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">Belum ada data laporan pada tanggal tersebut</td>
                                </tr>
                            `);
                        }

                        // Handle photo modal
                        document.getElementById('photo-modal-close').addEventListener('click', function () {
                            document.getElementById('photo-modal').classList.add('hidden');
                        });
                    }
                });
            }

            // Initial load
            loadLaporanData();

            // Update map and table on date change
            $('#date').on('change', function () {
                loadLaporanData();
            });
        });
    </script>

@endsection
