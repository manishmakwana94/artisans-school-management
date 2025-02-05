@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="page-title mb-0">Announcements</h3>
                    <a href="{{ route('teacher.announcements.create') }}" class="btn btn-primary">Add Announcement</a>
                </div>
                <div class="card-body">
                    <!-- Tabs for Admin and Teacher Announcements -->
                    <ul class="nav nav-tabs" id="announcementTabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="admin-tab" data-bs-toggle="tab" href="#adminAnnouncements">Admin
                                Announcements</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="teacher-tab" data-bs-toggle="tab" href="#teacherAnnouncements">Teacher
                                Announcements</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Admin Announcements Tab -->
                        <div class="tab-pane fade show active" id="adminAnnouncements">
                            <table id="admin-announcement-table" class="table table-bordered">
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
                                <tbody></tbody>
                            </table>
                        </div>

                        <!-- Teacher Announcements Tab -->
                        <div class="tab-pane fade" id="teacherAnnouncements">
                            <table id="teacher-announcement-table" class="table table-bordered">
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
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let activeTable = null;

            function getColumns() {
                return [{
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
                        sWidth: '10%',
                        searchable: false,
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
                        render: function(data) {
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
                ];
            }

            function loadTable(tabId) {
                let route = (tabId === "#adminAnnouncements") ?
                    '{{ route('teacher.announcements.get-admin-details') }}' :
                    '{{ route('teacher.announcements.get-details') }}';

                let tableId = (tabId === "#adminAnnouncements") ? "#admin-announcement-table" :
                    "#teacher-announcement-table";

                if (!$.fn.DataTable.isDataTable(tableId)) {
                    activeTable = $(tableId).DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: route,
                        columns: getColumns(),
                        order: [
                            [5, 'desc']
                        ]
                    });
                } else {
                    activeTable.ajax.reload();
                }
            }

            // Load only the active tab on page load
            loadTable("#adminAnnouncements");

            // Switch tabs dynamically
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {
                let target = $(event.target).attr("href");
                loadTable(target);
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
                                activeTable.ajax.reload();
                            } else {
                                showToast('error', 'Error occurred during deletion.', 'Error');
                            }
                        },
                        error: function() {
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
                        <th style="width: 20%;">Content</th>
                        <td style="text-align: justify;" >${announcement.content}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Target</th>
                        <td style="text-align: justify;">${announcement.target ? announcement.target : "N/A"}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Created By</th>
                        <td style="text-align: justify;">${announcement.user ? announcement.user.name : "N/A"} (${announcement.user ? announcement.user.role : "N/A"})</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Created At</th>
                        <td style="text-align: justify;">${new Date(announcement.created_at).toLocaleString()}</td>
                    </tr>
                </table>`,
                icon: "info",
                confirmButtonText: "Okay",
                customClass: {
                    popup: 'swal2-popup full-width',
                    htmlContainer: 'swal2-html-container',
                }
            });
        }
    </script>
@endpush
