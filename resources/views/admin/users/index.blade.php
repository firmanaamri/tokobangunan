@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-lg">Tambah User</a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Username</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3 font-mono">{{ $user->username }}</td>
                    <td class="px-4 py-3">{{ $user->email ?? '-' }}</td>
                    <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" aria-label="Edit" title="Edit" class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-user-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Hapus" title="Hapus" class="bg-red-600 hover:bg-red-700 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!},
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-user-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus user ini?',
                        text: 'Data tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</div>
@endsection
