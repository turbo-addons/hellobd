<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form method="POST" action="{{ isset($reporter) ? route('admin.reporters.update', $reporter) : route('admin.reporters.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($reporter))
                @method('PUT')
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                    <select name="type" id="reporter-type" class="form-control mt-1 w-full rounded border p-2 text-gray-900 dark:bg-gray-700 dark:text-white" required>
                        <option value="human" class="text-gray-900 dark:text-white" {{ old('type', $reporter->type ?? '') === 'human' ? 'selected' : '' }}>Human Reporter</option>
                        <option value="desk" class="text-gray-900 dark:text-white" {{ old('type', $reporter->type ?? '') === 'desk' ? 'selected' : '' }}>Desk Reporter</option>
                    </select>
                </div>

                @if(!isset($reporter))
                <div id="user-field">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('User') }} <span class="text-red-500">*</span></label>
                    <select name="user_id" class="form-control mt-1 w-full rounded border p-2 text-gray-900 dark:bg-gray-700 dark:text-white">
                        <option value="" class="text-gray-900 dark:text-white">Select User (for human reporter)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" class="text-gray-900 dark:text-white">{{ $user->full_name ?? $user->first_name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required for human reporters, leave empty for desk reporters</p>
                </div>
                @endif

                <div id="desk-name-field">
                    <label class="block text-sm font-medium">{{ __('Desk Name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="desk_name" value="{{ old('desk_name', $reporter->desk_name ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="e.g., Sports Desk, Politics Desk">
                    <p class="mt-1 text-xs text-gray-500">Required for desk reporters</p>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Designation') }}</label>
                    <input type="text" name="designation" value="{{ old('designation', $reporter->designation ?? '') }}" class="form-control mt-1 w-full rounded border p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Bio') }}</label>
                    <textarea name="bio" rows="4" class="form-control mt-1 w-full rounded border p-2">{{ old('bio', $reporter->bio ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Current Location') }}</label>
                    <input type="text" name="location" value="{{ old('location', $reporter->location ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="e.g., Dhaka, Bangladesh">
                    @if(isset($reporter) && $reporter->location_updated_at)
                    <p class="mt-1 text-xs text-gray-500">Last updated: {{ $reporter->location_updated_at->diffForHumans() }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Photo') }}</label>
                    @if(isset($reporter) && $reporter->photo_url)
                        <div class="mb-2">
                            <img src="{{ $reporter->photo_url }}" alt="Photo" class="h-32 w-32 rounded object-cover">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="form-control mt-1 w-full rounded border p-2">
                </div>

                @if(isset($reporter))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Verification Status') }}</label>
                    <select name="verification_status" class="form-control mt-1 w-full rounded border p-2 text-gray-900 dark:bg-gray-700 dark:text-white">
                        <option value="pending" class="text-gray-900 dark:text-white" {{ old('verification_status', $reporter->verification_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" class="text-gray-900 dark:text-white" {{ old('verification_status', $reporter->verification_status) === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" class="text-gray-900 dark:text-white" {{ old('verification_status', $reporter->verification_status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                @endif

                <div class="flex gap-2">
                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.reporters.index') }}" class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('reporter-type').addEventListener('change', function() {
            const userField = document.getElementById('user-field');
            const deskField = document.getElementById('desk-name-field');
            
            if (this.value === 'human') {
                if(userField) userField.style.display = 'block';
                if(deskField) deskField.style.display = 'none';
            } else {
                if(userField) userField.style.display = 'none';
                if(deskField) deskField.style.display = 'block';
            }
        });
        
        // Trigger on page load
        document.getElementById('reporter-type').dispatchEvent(new Event('change'));
    </script>
</x-layouts.backend-layout>
