<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form method="POST" action="{{ isset($reporter) ? route('admin.reporters.update', $reporter) : route('admin.reporters.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($reporter))
                @method('PUT')
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">{{ __('Type') }}</label>
                    <select name="type" id="reporter_type"
                        class="form-control mt-1 w-full rounded border p-2"
                        required>
                        <option value="human" {{ old('type', $reporter->type ?? '') === 'human' ? 'selected' : '' }}>
                            Human
                        </option>
                        <option value="desk" {{ old('type', $reporter->type ?? '') === 'desk' ? 'selected' : '' }}>
                            Desk
                        </option>
                    </select>
                </div>

                {{-- USER (for human) --}}
                <div id="user_field">
                    <label class="block text-sm font-medium">{{ __('User') }}</label>
                    <select name="user_id"
                        class="form-control mt-1 w-full rounded border p-2">
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $reporter->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name ?? ($user->first_name.' '.$user->last_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DESK NAME --}}
                <div id="desk_field">
                    <label class="block text-sm font-medium">{{ __('Desk Name') }}</label>
                    <input type="text" name="desk_name"
                        value="{{ old('desk_name', $reporter->desk_name ?? '') }}"
                        class="form-control mt-1 w-full rounded border p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Designation') }}</label>
                    <input type="text" name="designation" value="{{ old('designation', $reporter->designation ?? '') }}" class="form-control mt-1 w-full rounded border p-2">
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium">{{ __('Age') }}</label>
                        <input type="number" name="age" value="{{ old('age', $reporter->age ?? '') }}" class="form-control mt-1 w-full rounded border p-2" min="18" max="100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Location') }}</label>
                        <input type="text" name="location" value="{{ old('location', $reporter->location ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="Dhaka">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Experience') }}</label>
                        <input type="text" name="experience" value="{{ old('experience', $reporter->experience ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="5 years">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Specialization') }}</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $reporter->specialization ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="Politics, Sports, Technology">
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('Bio') }}</label>
                    <textarea name="bio" rows="4" class="form-control mt-1 w-full rounded border p-2">{{ old('bio', $reporter->bio ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Social Media Links') }}</label>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Facebook</label>
                            <input type="url" name="social_media[facebook]" value="{{ old('social_media.facebook', $reporter->social_media['facebook'] ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="https://facebook.com/username">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Twitter</label>
                            <input type="url" name="social_media[twitter]" value="{{ old('social_media.twitter', $reporter->social_media['twitter'] ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="https://twitter.com/username">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">LinkedIn</label>
                            <input type="url" name="social_media[linkedin]" value="{{ old('social_media.linkedin', $reporter->social_media['linkedin'] ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="https://linkedin.com/in/username">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Email</label>
                            <input type="email" name="social_media[instagram]" value="{{ old('social_media.instagram', $reporter->social_media['instagram'] ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="Enter Your Email">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">YouTube</label>
                            <input type="url" name="social_media[youtube]" value="{{ old('social_media.youtube', $reporter->social_media['youtube'] ?? '') }}" class="form-control mt-1 w-full rounded border p-2" placeholder="https://youtube.com/@username">
                        </div>
                    </div>
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
        function toggleReporterFields() {
            const type = document.getElementById('reporter_type').value;

            document.getElementById('user_field').style.display =
                type === 'human' ? 'block' : 'none';

            document.getElementById('desk_field').style.display =
                type === 'desk' ? 'block' : 'none';
        }
        document.getElementById('reporter_type').addEventListener('change', toggleReporterFields);
        toggleReporterFields(); // on load
    </script>

</x-layouts.backend-layout>
