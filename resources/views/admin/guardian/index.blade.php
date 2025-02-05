@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Page Title -->
                    <h3 class="page-title mb-0">Guardians</h3>
                </div>
                <div class="card-body">
                    <table id="guardian-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Teacher</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('#guardian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.guardians.get-details') }}', // Route to fetch the data
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Order by created_at (descending)
            });
        });
    </script>
@endpush
