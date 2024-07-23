@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">{{ __('Email atau Password tidak cocok!') }}</div>
    </div>
@endif
