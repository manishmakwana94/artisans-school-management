@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">{{ __('Teacher') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.teachers.store') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password Field with View Password Icon -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{ old('password') }}"
                                        placeholder="Enter or Generate Password">

                                    <!-- Eye Icon (Toggle Password Visibility) -->
                                    <span class="input-group-text" id="viewPasswordIcon" style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>

                                    <!-- Refresh Icon (Generate New Password) -->
                                    <span class="input-group-text" id="generatedPasswordIcon" style="cursor: pointer;">
                                        <i class="fa fa-refresh"></i>
                                    </span>
                                </div>
                                @error('password')
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


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const viewPasswordIcon = document.getElementById('viewPasswordIcon');
            const generatedPasswordIcon = document.getElementById('generatedPasswordIcon');

            // Function to generate a strong password
            function generateStrongPassword(length) {
                const upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const lowerCase = 'abcdefghijklmnopqrstuvwxyz';
                const numbers = '0123456789';
                const specialCharacters = '@$!%*?&';

                let password = '';

                // Ensure at least one character from each category
                password += upperCase.charAt(Math.floor(Math.random() * upperCase.length));
                password += lowerCase.charAt(Math.floor(Math.random() * lowerCase.length));
                password += numbers.charAt(Math.floor(Math.random() * numbers.length));
                password += specialCharacters.charAt(Math.floor(Math.random() * specialCharacters.length));

                // Fill remaining characters randomly
                const allCharacters = upperCase + lowerCase + numbers + specialCharacters;
                for (let i = password.length; i < length; i++) {
                    password += allCharacters.charAt(Math.floor(Math.random() * allCharacters.length));
                }

                // Shuffle password characters
                return password.split('').sort(() => Math.random() - 0.5).join('');
            }

            // Function to toggle password visibility
            function togglePasswordVisibility() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    viewPasswordIcon.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    passwordField.type = 'password';
                    viewPasswordIcon.innerHTML = '<i class="fa fa-eye"></i>';
                }
            }

            // Function to generate password with loading animation
            function generatePasswordWithSpinner() {
                generatedPasswordIcon.innerHTML = '<i class="fa fa-refresh fa-spin"></i>';

                setTimeout(() => {
                    const newPassword = generateStrongPassword(8);
                    passwordField.value = newPassword; // Keep it editable

                    generatedPasswordIcon.innerHTML = '<i class="fa fa-refresh"></i>';
                }, 1000);
            }

            // Attach event listeners
            viewPasswordIcon.addEventListener('click', togglePasswordVisibility);
            generatedPasswordIcon.addEventListener('click', generatePasswordWithSpinner);
        });
    </script>
@endpush
