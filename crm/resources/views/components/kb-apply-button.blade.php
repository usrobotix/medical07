<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-indigo-600 text-white border border-indigo-600 rounded-md hover:bg-indigo-700 hover:border-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800']) }}>
    {{ $slot->isEmpty() ? 'Применить' : $slot }}
</button>
