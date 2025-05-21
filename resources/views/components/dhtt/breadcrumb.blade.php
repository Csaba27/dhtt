<nav class="text-gray-600 dark:text-gray-100 text-md mb-5">
    <ul class="flex gap-1">
        @foreach($items as $item)
            <li class="flex items-center">
                @if (!$loop->last)
                    <a href="{{ $item['url'] }}" class="text-blue-500 hover:underline">{{ $item['label'] }}</a>
                    <span class="mx-2">/</span>
                @else
                    <span class="text-gray-500 dark:text-gray-300">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
