@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Buat User Baru</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        @include('admin.users._form')

        <div class="mt-4 flex gap-2">
            <button class="bg-emerald-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="bg-slate-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection
