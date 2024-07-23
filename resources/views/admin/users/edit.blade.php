@extends('layouts.admin')

@section('title')
    Admin - Edit User
@endsection

@section('content')
    <div class="w-4/5 p-4 h-full">
        <div class="bg-white w-full h-full rounded-3xl overflow-auto px-10 py-8">
            <h1 class="font-bold text-2xl">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h1>
            <form action="{{ isset($user) ? route('dashboard.users.update', $user) : route('dashboard.users.store') }}" method="POST" class="w-full mt-4">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('name') border-red-500 @enderror" required />
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('email') border-red-500 @enderror" required />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @if (!isset($user) || (isset($user) && empty($user->password)))
                    <div class="mb-5">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('password') border-red-500 @enderror" required />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('password_confirmation') border-red-500 @enderror" required />
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
                <div class="mb-5">
                    <label for="nik" class="block mb-2 text-sm font-medium text-gray-700">NIK</label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik', isset($user) ? $user->nik : '') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('nik') border-red-500 @enderror" required />
                    @error('nik')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="level" class="block mb-2 text-sm font-medium text-gray-700">Level</label>
                    <select id="level" name="level" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('level') border-red-500 @enderror" required>
                        <option value="">Pilih Level</option>
                        <option value="1" {{ old('level', isset($user) && $user->level == 1 ? 'selected' : '') }}>Admin</option>
                        <option value="2" {{ old('level', isset($user) && $user->level == 2 ? 'selected' : '') }}>Petugas</option>
                    </select>
                    @error('level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="no_telepon" class="block mb-2 text-sm font-medium text-gray-700">No Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', isset($user) ? $user->no_telepon : '') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:outline-none focus:ring-2 @error('no_telepon') border-red-500 @enderror" required />
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    {{ isset($user) ? 'Update' : 'Tambah User' }}
                </button>
            </form>
        </div>
    </div>
@endsection
