@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">{{ __('Announcement') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.announcements.store') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="content" class="form-label">{{ __('Content') }}</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                    value="{{ old('content') }}" required autofocus></textarea>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
