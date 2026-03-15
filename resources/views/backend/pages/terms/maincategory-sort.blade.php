<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <x-card>
        <x-slot name="header">
            {{ __('Sort Main Menu') }}
        </x-slot>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold mb-2">{{ __('Available Categories') }}</h3>
                <ul id="defaultBox" class="space-y-2 p-2 border rounded bg-gray-50">
                    @foreach($defaultCategories as $category)
                        <li data-id="{{ $category->id }}" class="p-3 border rounded bg-white cursor-move">
                            {{ $category->name }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-2">{{ __('Sorted Categories') }}</h3>
                <ul id="sortedBox" class="space-y-2 p-2 border rounded bg-gray-50">
                    @foreach($sortedCategories as $category)
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
            </div>
        </div>
    </x-card>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultBox = document.getElementById('defaultBox');
    const sortedBox = document.getElementById('sortedBox');

    new Sortable(defaultBox, { group: 'shared', animation: 150, ghostClass: 'sortable-ghost' });
    new Sortable(sortedBox, { group: 'shared', animation: 150, ghostClass: 'sortable-ghost' });

    document.getElementById('saveOrder').addEventListener('click', function () {
        let items = [];

        // sortedBox items → save order
        sortedBox.querySelectorAll('li').forEach((li, index) => {
            items.push({ id: li.dataset.id, order: index + 1 });
        });

        // defaultBox items → remove order (null)
        defaultBox.querySelectorAll('li').forEach((li) => {
            items.push({ id: li.dataset.id, order: null });
        });

        fetch('{{ route('admin.maincategory.sort.save') }}', {
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
                if (window.$toast) window.$toast.success(data.message);
                else alert(data.message);
            } else {
                if (window.$toast) window.$toast.error(data.message || 'Something went wrong');
                else alert(data.message || 'Something went wrong');
            }
        })
        .catch(() => {
            if (window.$toast) window.$toast.error('Something went wrong');
            else alert('Something went wrong');
        });
    });
});

</script>


    <style>
    #defaultBox li, #sortedBox li {
        user-select: none;
    }
    .sortable-ghost {
        opacity: 0.6;
        background-color: #f3f4f6;
    }
    </style>
</x-layouts.backend-layout>
