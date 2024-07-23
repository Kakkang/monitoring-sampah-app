@extends('layouts.petugas')

@section('title')
    Edit Laporan
@endsection

@section('content')

<div class="w-full h-full">
    <div class="bg-white w-full h-full overflow-auto">
        <div class="flex gap-4 items-center mb-6">
            <a class="text-lg" href="{{ route('dashboard.index') }}">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <h1 class="font-bold text-2xl">Edit Laporan</h1>
        </div>
        <form action="{{ route('laporan.update', $laporan->id_laporan) }}" method="POST" enctype="multipart/form-data" class="w-full">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="foto" class="block mb-2 text-sm font-medium text-gray-900">Foto</label>
                <div class="flex flex-col items-center">
                    @if($laporan->foto)
                        <img src="{{ asset('storage/' . $laporan->foto) }}" id="current-image" class="mb-2 w-full aspect-square object-cover" alt="Preview Foto">
                    @endif
                    <video id="video" autoplay class="mb-2 w-full hidden"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="preview" class="mt-2 w-full aspect-square object-cover hidden">
                    <button type="button" id="capture-button" onclick="captureImage()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2">Ambil Foto</button>
                    <button type="button" id="retake-button" onclick="retakeImage()" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-2 mb-2 hidden">Ulangi</button>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                </div>
            </div>
            <div class="mb-5">
                <label for="kecamatan" class="block mb-2 text-sm font-medium text-gray-900">Kecamatan</label>
                <select id="kecamatan" name="kecamatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == old('kecamatan', $laporan->kecamatan) ? 'selected' : '' }}>
                            {{ $kecamatan->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label for="desa" class="block mb-2 text-sm font-medium text-gray-900">Desa</label>
                <select id="desa" name="desa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="">Pilih Desa</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="mb-5">
                <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>{{ old('keterangan', $laporan->keterangan) }}</textarea>
            </div>
            <div class="mb-5">
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $laporan->latitude) }}" required>
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $laporan->longitude) }}" required>
            </div>
            <div class="mb-5">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Mendapatkan lokasi GPS pengguna
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        },
        function(error) {
            console.log("Error obtaining location: " + error.message);
            alert("Tidak dapat mendapatkan lokasi. Pastikan GPS diaktifkan.");
        },
        {
            enableHighAccuracy: true, // Meminta akurasi tinggi
            timeout: 5000, // Menunggu maksimal 5 detik
            maximumAge: 0 // Tidak menggunakan data lokasi yang sudah ada
        }
    );
} else {
    alert("Geolocation is not supported by this browser.");
}


    let stream;
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const captureButton = document.getElementById('capture-button');
    const retakeButton = document.getElementById('retake-button');
    const currentImage = document.getElementById('current-image');

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream;
                video.srcObject = mediaStream;
                video.classList.remove('hidden');
                captureButton.classList.remove('hidden');
                retakeButton.classList.add('hidden');
                preview.classList.add('hidden');
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    function captureImage() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Get current date and time
        const now = new Date();
        const timestamp = now.toLocaleString();

        // Set font styles
        context.font = '20px Arial';
        context.fillStyle = 'white';
        context.strokeStyle = 'black';
        context.lineWidth = 2;

        // Get text metrics
        const textWidth = context.measureText(timestamp).width;
        const textHeight = 20; // Approximate height

        // Set the position for the timestamp (bottom right)
        const x = canvas.width - textWidth - 10;
        const y = canvas.height - 10;

        // Draw the timestamp
        context.strokeText(timestamp, x, y);
        context.fillText(timestamp, x, y);

        const dataURL = canvas.toDataURL('image/png');
        preview.src = dataURL;
        preview.classList.remove('hidden');
        preview.classList.add('block');

        video.classList.add('hidden');
        captureButton.classList.add('hidden');
        retakeButton.classList.remove('hidden');

        // Mengatur data URL sebagai nilai dari input file
        const inputFile = document.getElementById('foto');
        fetch(dataURL)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], "captured_image.png", { type: "image/png" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputFile.files = dataTransfer.files;
            });
    }

    function retakeImage() {
        preview.classList.add('hidden');
        preview.classList.remove('block');

        captureButton.classList.remove('hidden');
        retakeButton.classList.add('hidden');

        video.classList.remove('hidden');
        currentImage.classList.add('hidden'); // Hide the current image
        startCamera();
    }

    // Start the camera when the page loads
    window.addEventListener('load', function() {
        if (currentImage) {
            video.classList.add('hidden');
            captureButton.classList.add('hidden');
            retakeButton.classList.remove('hidden');
            preview.classList.add('hidden');
            currentImage.classList.remove('hidden'); // Ensure the current image is shown
        } else {
            startCamera();
        }
    });

    // Dynamic Desa loading
    document.getElementById('kecamatan').addEventListener('change', function() {
        const kecamatanId = this.value;
        const desaSelect = document.getElementById('desa');
        
        // Clear existing options
        desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
        
        // Fetch desa data for selected kecamatan
        fetch(`/get-desa/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(desa => {
                    const option = document.createElement('option');
                    option.value = desa.id;
                    option.textContent = desa.name;
                    // Set selected option if it matches existing value
                    if (desa.id == '{{ old('desa', $laporan->desa) }}') {
                        option.selected = true;
                    }
                    desaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching desa data:', error));
    });

    // Set the initial value for Desa select
    window.addEventListener('load', function() {
        const selectedKecamatan = '{{ old('kecamatan', $laporan->kecamatan) }}';
        if (selectedKecamatan) {
            document.getElementById('kecamatan').value = selectedKecamatan;
            document.getElementById('kecamatan').dispatchEvent(new Event('change'));
        }
    });
</script>

@endsection
