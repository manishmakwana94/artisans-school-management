@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">{{ __('Announcement') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('teacher.announcements.store') }}">
                            @csrf

                            <!-- content Field -->
                            <div class="mb-3">
                                <label for="content" class="form-label">{{ __('Content') }}</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                    value="{{ old('content') }}" required autofocus></textarea>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- target Field -->
                            <div class="mb-3">
                                <lable for="target" class="form-label">{{ __('Target') }}</lable>
                                <select class="form-select @error('target') is-invalid @enderror" id="target"
                                    name="target" value="{{ old('target') }}" required autofocus>
                                    <option selected>Select Target</option>
                                    <option value="guardians">Guardians</option>
                                    <option value="students">Students</option>
                                    <option value="both">Both</option>
                                </select>
                                @error('target')
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
