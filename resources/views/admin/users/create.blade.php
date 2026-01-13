@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4 md:p-6">
    
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition">Users</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Buat User Baru</h1>
        <p class="text-slate-500 text-sm mt-1">Tambahkan pengguna baru untuk mengakses sistem inventaris.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            {{-- Form Content --}}
            <div class="p-6 space-y-4">
                {{-- Include Form Partial --}}
                @include('admin.users._form')
            </div>

            {{-- Action Buttons Footer --}}
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse md:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="w-full md:w-auto text-center px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-600 font-medium hover:bg-slate-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection