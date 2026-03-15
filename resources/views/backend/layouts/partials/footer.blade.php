@php
    $versionFile = base_path('version.json');
    $versionData = file_exists($versionFile) ? json_decode(file_get_contents($versionFile), true) : [];
    $appVersion = $versionData['version'] ?? '0.0.0';
@endphp

<footer class="mt-auto border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
    <div class="px-4 py-3 sm:px-6">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-xs text-gray-500 dark:text-gray-400">
            <p> Developed by <a href="https://wp-turbo.com/" target="_blank">WP-TURBO</a> </p>
        </div>
    </div>
</footer>
