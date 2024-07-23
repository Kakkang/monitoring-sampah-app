@extends('layouts.petugas')

@section('title')
    Dashboard
@endsection

@section('content')
  <!-- TOPBAR -->
  <div class="flex justify-between items-center">
        <div class="flex gap-3 items-center">
          <img
            src="/images/janitor.png"
            class="rounded-2xl w-14 h-14 bg-cover"
            alt="PROFILE"
          />
          <div class="w-full flex flex-col justify-center items-start">
            <div class="text-xl font-bold">{{ $authUser->name }}</div>
            <div class="text-sm font-light">{{ $authUser->level == 1 ? 'Admin' : 'Petugas' }}</div>
          </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <button
              type="submit"
              class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5"
            >
              Logout
            </button>
        </form>
  </div>

  <!-- BODY -->
  <div class="mt-14">
    <a
      href="{{ route('laporan.create') }}"
      class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-all"
    >
      <span class="mr-2"><i class="fa-solid fa-file-circle-plus"></i></span>
      Buat Laporan
    </a>

    <h1 class="mt-4 text-xl font-bold">Riwayat Laporan</h1>
    <div class="mt-4 flex flex-col gap-4">
      @foreach($laporans as $laporan)
      <div class="flex justify-between items-start gap-2 relative">
        <div class="flex gap-2">
          <img
            src="{{ asset('storage/' . $laporan->foto) }}"
            class="w-24 h-24 rounded-lg object-cover"
            alt="BUKTI"
          />
          <div class="flex flex-col justify-between">
            <div>
              <div class="text-[10px]">{{ $laporan->created_at->format('d F Y') }}</div>
              <div class="text-xs font-semibold">Keterangan</div>
              <div class="text-xs line-clamp-2 overflow-hidden">
                {{ $laporan->keterangan }}
              </div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-sm capitalize">
                {{ ucfirst(strtolower($laporan->village->name)) }}, {{ ucfirst(strtolower($laporan->district->name)) }}
              </div>
              <a 
                class="absolute right-3 bottom-0 text-[8px] bg-blue-400 rounded-lg px-2 text-white py-1"
                href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                Cek Lokasi
            </a>
            </div>
          </div>
        </div>
        <div
          class="w-fit h-fit text-xs font-medium rounded-lg px-5 py-2 text-center me-2 mb-2
            {{ $laporan->status == 1 ? 'text-green-700 border border-green-700' : ($laporan->status == 2 ? 'text-red-700 border border-red-700' : '') }}"
        >
          {{ $laporan->status == 1 ? 'Valid' : ($laporan->status == 2 ? 'Ditolak' : '') }}
        </div>
        <div class="flex gap-1 {{ $laporan->status != 0 ? 'hidden' : '' }}">
          <a
            href="{{ route('laporan.edit', $laporan->id_laporan) }}"
            class="text-white text-xs bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-2.5 py-2.5 transition-all"
          >
            <i class="fa-solid fa-pencil"></i>
          </a>
          <form
            method="POST"
            action="{{ route('laporan.destroy', $laporan->id_laporan) }}"
            class="inline"
          >
            @csrf
            @method('DELETE')
            <button
              type="submit"
              class="text-white text-xs bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-2.5 py-2.5 transition-all delete-user"
              data-id="{{ $laporan->id_laporan }}"
            >
              <i class="fa-solid fa-trash"></i>
            </button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Script Sweet Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script>
      // Sweet Alert untuk konfirmasi hapus
      document.addEventListener('DOMContentLoaded', function () {
          const deleteButtons = document.querySelectorAll('.delete-user');
          deleteButtons.forEach(button => {
              button.addEventListener('click', function (e) {
                  e.preventDefault();
                  const form = this.closest('form');
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
                          form.submit();
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
