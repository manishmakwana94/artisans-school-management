@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Page Title -->
                    <h3 class="page-title mb-0">Students</h3>

                    <!-- Add Teacher Button -->
                    <a href="{{ route('teacher.students.create') }}" class="btn btn-primary">Add Student</a>
                </div>
                <div class="card-body">
                    <table id="teacher-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Guardian</th>
                                <th>Created At</th>
                                <th>Actions</th>
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
        let table;
        $(document).ready(function() {

            table = $('#teacher-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('teacher.students.get-details') }}', // Route to fetch the data
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
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
                        data: 'guardian',
                        name: 'guardian.name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Order by created_at (descending)
            });
        });

        function deleteStudent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "students/" + id,
                        type: "DELETE",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showToast('success', 'Deleted Successfully.', 'Success');
                                table.ajax.reload();
                            } else {
                                showToast('error', 'Error occurred during deletion.',
                                    'Error');
                            }
                        },
                        error: function(json) {
                            showToast('error', 'Error occurred during deletion.', 'Error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
