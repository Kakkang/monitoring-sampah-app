<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    
  </head>
  <body class="relative">
    <div class="relative w-screen h-screen flex gap-2 z-10">
      <div class="w-1/5 py-4 px-4 h-full flex flex-col justify-between">
        <div>
          <div class="px-8 my-6 text-white">
            <img
              src="{{ asset('/images/petugas-profile.png') }}"
              class="w-16 h-16 rounded-lg bg-cover"
              alt=""
            />
            <h2 class="text-lg font-bold mt-4">{{ $authUser->name }}</h2>
            <p class="text-sm font-light">{{ $authUser->level == 1 ? 'Admin' : 'Petugas' }}</p>
          </div>
          <div class="flex flex-col gap-2 px-2">
            <a href="{{ route('dashboard.index') }}" class="w-full px-6 py-2 font-bold rounded-lg {{ Request::is('dashboard') ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black transition-all cursor-pointer' }}">
              Dashboard
            </a>
            <a href="{{ route('dashboard.users') }}" class="w-full px-6 py-2 font-bold rounded-lg {{ Request::is('dashboard/users') ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black transition-all cursor-pointer' }}">
              Data User
            </a>
          </div>
        </div>
        <div class="px-2">
          <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <button type="submit" class="bg-white w-full px-6 py-2 text-lg font-bold rounded-lg text-center cursor-pointer">
              Logout
            </button>
          </form>
        </div>
      </div>

      @yield('content')
    </div>

    <img
      src="{{ asset('/images/background.png') }}"
      class="absolute top-0 left-0 w-screen h-screen object-cover"
    />
    <div
      class="absolute top-0 left-0 w-screen h-screen bg-black opacity-80"
    ></div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  </body>
</html>
