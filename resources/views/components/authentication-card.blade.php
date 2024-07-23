<div class="relative">
    <div class="relative min-h-screen flex flex-col z-10 sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-full flex flex-col gap-6 items-center sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex gap-4 items-center justify-center">
                <img
                    src="{{ asset('/images/pemda-garut.png') }}"
                    class="w-20 h-20"
                />
                <div class="">
                    <div class="text-xl font-bold">Dinas Lingkungan Hidup</div>
                    <div class="text-base font-normal">Kabupaten Garut</div>
                </div>
            </div>
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>

    <img
      src="{{ asset('/images/background.png') }}"
      class="absolute top-0 left-0 w-screen h-screen object-cover"
    />
    <div
      class="absolute top-0 left-0 w-screen h-screen bg-black opacity-80"
    ></div>
</div>
