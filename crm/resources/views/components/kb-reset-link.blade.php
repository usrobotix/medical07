@props(['href'])
<a href="{{ $href }}" {{ $attributes->except('href')->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 dark:focus:ring-offset-gray-800']) }}>
    {{ $slot->isEmpty() ? 'Сбросить' : $slot }}
</a>
