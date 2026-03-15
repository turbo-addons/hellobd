<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <x-card>
        <x-slot name="header">
            {{ __('Sort Other Categories') }}
        </x-slot>

        <ul id="sortable" class="space-y-2">
            @foreach($categories as $category)
                <li data-id="{{ $category->id }}" class="p-3 border rounded bg-white cursor-move">
                    {{ $category->name }}
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <button id="saveOrder" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('Update Order') }}
            </button>
        </div>
    </x-card>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sortableEl = document.getElementById('sortable');

            if (sortableEl) {
                new Sortable(sortableEl, {
                    animation: 150,
                    ghostClass: 'sortable-ghost'
                });
            }

            document.getElementById('saveOrder').addEventListener('click', function () {
                let items = [];
                document.querySelectorAll('#sortable li').forEach((li, index) => {
                    items.push({
                        id: li.dataset.id,
                        order: index + 1
                    });
                });

                fetch('{{ route('admin.category.sort.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ items })
                })
                .then(res => res.json())
                .then((data) => {
                    if (data.success) {
                        // Use toast if available
                        if (window.$toast) {
                            window.$toast.success('{{ __("Category order saved successfully") }}');
                        } else {
                            // fallback alert
                            alert('{{ __("Category order saved successfully") }}');
                        }
                    } else {
                        if (window.$toast) {
                            window.$toast.error('{{ __("Something went wrong") }}');
                        } else {
                            alert('{{ __("Something went wrong") }}');
                        }
                    }
                })
                .catch(() => {
                    if (window.$toast) {
                        window.$toast.error('{{ __("Something went wrong") }}');
                    } else {
                        alert('{{ __("Something went wrong") }}');
                    }
                });
            });
        });
    </script>


    <style>
    #sortable li {
        user-select: none;
    }
    .sortable-ghost {
        opacity: 0.6;
        background-color: #f3f4f6;
    }
    </style>
</x-layouts.backend-layout>
