@props(['placement' => 'sidebar'])

<div id="ad-widget-{{ $placement }}" class="ad-widget" data-placement="{{ $placement }}">
    <!-- Ad will be loaded here -->
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const placement = '{{ $placement }}';
    const container = document.getElementById('ad-widget-' + placement);
    
    fetch('/api/ads/' + placement)
        .then(response => response.json())
        .then(data => {
            if (data.ad) {
                const ad = data.ad;
                let html = '<div class="ad-container bg-gray-100 dark:bg-gray-800 rounded-lg p-4">';
                html += '<div class="text-xs text-gray-500 mb-2">Sponsored</div>';
                
                if (ad.image) {
                    html += '<a href="' + ad.click_url + '" target="_blank" rel="noopener">';
                    html += '<img src="' + ad.image + '" alt="' + ad.title + '" class="w-full rounded">';
                    html += '</a>';
                }
                
                html += '<div class="mt-2">';
                html += '<h3 class="font-semibold text-gray-900 dark:text-white">' + ad.title + '</h3>';
                if (ad.content) {
                    html += '<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">' + ad.content + '</p>';
                }
                if (ad.link_url) {
                    html += '<a href="' + ad.click_url + '" target="_blank" rel="noopener" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">Learn More →</a>';
                }
                html += '</div></div>';
                
                container.innerHTML = html;
            }
        })
        .catch(error => console.error('Error loading ad:', error));
});
</script>
@endpush
