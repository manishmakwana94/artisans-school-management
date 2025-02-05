<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- Add SweetAlert2 JS -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>

<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>


<script>
    window.showToast = function(icon, message, title = '') {
        const Toast = Swal.mixin({
            toast: true,
            customClass: {
                title: 'toast-title',
                container: 'toast-container-custom'
            },
            animation: false,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 5000,
            color: '#fff',
            background: '#282d3e',
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Display Toast Notification
        Toast.fire({
            icon: icon || 'success', // Fix: Use function parameters
            title: title,
            text: message
        });
    };

    @if (session('swal-toast'))
        const swalData = @json(session('swal-toast'));
        showToast(swalData.type, swalData.message, swalData.title);
    @endif
</script>
