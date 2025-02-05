@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">{{ __('Teacher') }}</div>

                    <div class="card-body">
                        <form action="{{ route('teacher.students.update', $student->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ $student->name }}" required autofocus>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ $student->email }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- guardian Field -->
                            <div class="mb-3">
                                <label for="guardian_id" class="form-label">{{ __('Guardian') }}</label>
                                <select class="form-select @error('guardian_id') is-invalid @enderror" id="guardian_id"
                                    name="guardian_id" required>
                                    <option value="">Select Guardian</option>
                                    @foreach ($guardians as $guardian)
                                        <option value="{{ $guardian->id }}"
                                            {{ $student->guardian_id == $guardian->id ? 'selected' : '' }}>
                                            {{ $guardian->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
