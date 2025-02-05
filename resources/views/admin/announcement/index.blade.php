@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Page Title -->
                    <h3 class="page-title mb-0">Announcements</h3>

                    <!-- Add Announcement Button -->
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">Add Announcement</a>
                </div>
                <div class="card-body">
                    <table id="announcement-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Author</th>
                                <th>Role</th>
                                <th>Target</th>
                                <th>Content</th>
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
        $(document).ready(function() {

            $('#announcement-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.announcements.get-details') }}', // Route to fetch the data
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        sWidth: '10%',
                        searchable: false,
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        className: 'capitalize-text',
                        sWidth: '20%'

                    },
                    {
                        data: 'user.role',
                        name: 'user_role',
                        className: 'capitalize-text',
                        sWidth: '10%'
                    },
                    {
                        data: 'target',
                        name: 'target',
                        className: 'capitalize-text',
                        sWidth: '10%'
                    },
                    {
                        data: 'content',
                        name: 'content',
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        },
                        sWidth: '20%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        sWidth: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '15%'
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Order by created_at (descending)
            });
        });

        function deleteAnnouncement(id) {
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
                        url: "announcements/" + id,
                        type: "DELETE",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showToast('success', 'Deleted Successfully.', 'Success');

                                $('.user-' + id).closest('tr').remove();
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

        function viewAnnouncement(data) {
            let announcement = JSON.parse(data.getAttribute('data-announcement'));

            Swal.fire({
                title: "Announcement Details",
                html: `
                <table class="table table-bordered">
                    <tr>
                        <th>Content</th>
                        <td>${announcement.content}</td>
                    </tr>
                    <tr>
                        <th>Target</th>
                        <td>${announcement.target?announcement.target: "N/A"}</td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>${announcement.user ? announcement.user.name : "N/A"} (${announcement.user ? announcement.user.role : "N/A"})</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>${new Date(announcement.created_at).toLocaleString()}</td>
                    </tr>
                </table>`,
                icon: "info",
                confirmButtonText: "Okay",
                customClass: {
                    popup: 'swal2-popup full-width', // Apply full width class here
                    htmlContainer: 'swal2-html-container',
                }
            });
        }
    </script>
@endpush
