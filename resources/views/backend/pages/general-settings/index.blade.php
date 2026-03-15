<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('General Settings') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" 
                          action="{{ isset($settings) ? route('admin.general_settings.update', $settings->id) : route('admin.general_settings.store') }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($settings) && $settings->id)
                            @method('PUT')
                        @endif
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">{{ __('Site Title') }}</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $settings->title ?? '') }}"
                                           placeholder="{{ __('Enter site title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $settings->email ?? '') }}"
                                           placeholder="{{ __('Enter email address') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="contact" class="form-label">{{ __('Contact Number') }}</label>
                                    <input type="text" 
                                           class="form-control @error('contact') is-invalid @enderror" 
                                           id="contact" 
                                           name="contact" 
                                           value="{{ old('contact', $settings->contact ?? '') }}"
                                           placeholder="{{ __('Enter contact number') }}">
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="facebook" class="form-label">{{ __('Facebook URL') }}</label>
                                    <input type="url" 
                                           class="form-control @error('facebook') is-invalid @enderror" 
                                           id="facebook" 
                                           name="facebook" 
                                           value="{{ old('facebook', $settings->facebook ?? '') }}"
                                           placeholder="https://facebook.com/username">
                                    @error('facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="twitter" class="form-label">{{ __('Twitter URL') }}</label>
                                    <input type="url" 
                                           class="form-control @error('twitter') is-invalid @enderror" 
                                           id="twitter" 
                                           name="twitter" 
                                           value="{{ old('twitter', $settings->twitter ?? '') }}"
                                           placeholder="https://twitter.com/username">
                                    @error('twitter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="linkedin" class="form-label">{{ __('LinkedIn URL') }}</label>
                                    <input type="url" 
                                           class="form-control @error('linkedin') is-invalid @enderror" 
                                           id="linkedin" 
                                           name="linkedin" 
                                           value="{{ old('linkedin', $settings->linkedin ?? '') }}"
                                           placeholder="https://linkedin.com/in/username">
                                    @error('linkedin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="youtube" class="form-label">{{ __('YouTube URL') }}</label>
                                    <input type="url" 
                                           class="form-control @error('youtube') is-invalid @enderror" 
                                           id="youtube" 
                                           name="youtube" 
                                           value="{{ old('youtube', $settings->youtube ?? '') }}"
                                           placeholder="https://youtube.com/c/username">
                                    @error('youtube')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Uploads -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="site_logo" class="form-label">{{ __('Site Logo') }}</label>
                                    <input type="file" 
                                           class="form-control @error('site_logo') is-invalid @enderror" 
                                           id="site_logo" 
                                           name="site_logo"
                                           accept="image/*">
                                    @error('site_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($settings->site_logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/website_settings/' . $settings->site_logo) }}" alt="Site Logo" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fav_icon" class="form-label">{{ __('Favicon') }}</label>
                                    <input type="file" 
                                           class="form-control @error('fav_icon') is-invalid @enderror" 
                                           id="fav_icon" 
                                           name="fav_icon"
                                           accept="image/x-icon,image/png,image/svg+xml">
                                    @error('fav_icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($settings->fav_icon)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/website_settings/' . $settings->fav_icon) }}" alt="Fav icon" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Fields -->
                            <div class="col-md-12">
                                <hr>
                                <h5 class="mb-3">{{ __('Additional Information') }}</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="other_one" class="form-label">Other Field 1</label>
                                    <input type="text" 
                                           class="form-control @error('other_one') is-invalid @enderror" 
                                           id="other_one" 
                                           name="other_one" 
                                           value="{{ old('other_one', $settings->other_one ?? '') }}"
                                           placeholder="{{ __('Enter additional information one') }}">
                                    @error('other_one')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="other_two" class="form-label">Other Field 2</label>
                                    <input type="text" 
                                           class="form-control @error('other_two') is-invalid @enderror" 
                                           id="other_two" 
                                           name="other_two" 
                                           value="{{ old('other_two', $settings->other_two ?? '') }}"
                                           placeholder="{{ __('Enter additional information two') }}">
                                    @error('other_two')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="other_three" class="form-label">Other Field 3</label>
                                    <input type="text" 
                                           class="form-control @error('other_three') is-invalid @enderror" 
                                           id="other_three" 
                                           name="other_three" 
                                           value="{{ old('other_three', $settings->other_three ?? '') }}"
                                           placeholder="{{ __('Enter additional information two') }}">
                                    @error('other_three')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="other_four" class="form-label">Other Field 4</label>
                                    <input type="text" 
                                           class="form-control @error('other_four') is-invalid @enderror" 
                                           id="other_four" 
                                           name="other_four" 
                                           value="{{ old('other_four', $settings->other_four ?? '') }}"
                                           placeholder="{{ __('Enter additional information two') }}">
                                    @error('other_four')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="other_five" class="form-label">Other Field 5</label>
                                    <input type="text" 
                                           class="form-control @error('other_five') is-invalid @enderror" 
                                           id="other_five" 
                                           name="other_five" 
                                           value="{{ old('other_five', $settings->other_five ?? '') }}"
                                           placeholder="{{ __('Enter additional information two') }}">
                                    @error('other_five')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ __('Save Settings') }}
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('Back to Dashboard') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.backend-layout>