@if ($message = Session::get('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ $message }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timerProgressBar: true
            });
        });
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $message }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timerProgressBar: true
            });
        });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: '{{ $message }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timerProgressBar: true
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errors = @json($errors->all());
            const html = '<ul style="text-align:left;margin:0;padding-left:1.2rem;">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>';
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                html: html,
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-xl' }
            });
        });
    </script>
@endif
