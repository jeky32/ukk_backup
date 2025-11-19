<a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => 'block bg-white rounded-lg shadow p-4 transition cursor-pointer']) }}>
    <div class="flex justify-between items-center mb-2">
        <h6 class="font-bold text-gray-800">{{ $title }}</h6>
        <span class="text-xs text-gray-600 bg-gray-100 rounded px-2 py-1">{{ $meta ?? '' }}</span>
    </div>
    <div class="text-sm text-gray-700 mb-1">{{ $slot }}</div>
</a>
