@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if (session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif

            @if (session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: "Please check the form for errors."
                });
            @endif

            // Global Delete Confirmation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-confirm')) {
                    const btn = e.target.closest('.delete-confirm');
                    e.preventDefault();
                    const name = btn.getAttribute('data-name') || 'this record';
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to permanently delete "${name}". This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn bg-danger text-white px-4 py-2 rounded-md',
                            cancelButton: 'btn bg-default-100 text-default-700 px-4 py-2 rounded-md'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.closest('form').submit();
                        }
                    });
                }
            });
        });
    </script>
@endif
