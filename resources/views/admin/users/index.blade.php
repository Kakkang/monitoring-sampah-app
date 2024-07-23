@extends('layouts.admin')

@section('title')
    Admin - Manajemen User
@endsection

@section('content')
    <div class="w-4/5 p-4 h-full">
        <div class="bg-white w-full h-full rounded-3xl overflow-auto px-10 py-8">
            <h1 class="font-bold text-2xl mb-6">Data User</h1>
            <a href="{{ route('dashboard.users.create') }}" class="mt-4 mb-4 w-fit bg-blue-500 text-white p-2 rounded flex items-center justify-center">
                <span class="mr-2"><i class="fas fa-user-plus"></i></span> Tambah User
            </a>
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
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                NIK
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Level
                            </th>
                            <th scope="col" class="px-6 py-3">
                                No Telepon
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $user->nik }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->level == 1)
                                        Admin
                                    @elseif ($user->level == 2)
                                        Petugas
                                    @else
                                        {{ $user->level }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $user->no_telepon }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                    <a href="{{ route('dashboard.users.edit', $user) }}" class="bg-purple-500 text-white p-2 rounded flex items-center">
                                        <span class="mr-1"><i class="fas fa-edit"></i></span> Edit
                                    </a>
                                    <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white p-2 rounded delete-user flex items-center">
                                            <span class="mr-1"><i class="fas fa-trash-alt"></i></span> Delete
                                        </button>
                                    </form>
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
        // Sweet Alert untuk konfirmasi hapus
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-user');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const userId = this.getAttribute('data-id');
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
                            // Submit form untuk menghapus user
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
