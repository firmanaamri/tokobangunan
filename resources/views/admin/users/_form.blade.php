<div class="space-y-4">
    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Nama <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 @error('name') border-red-500 @enderror">
        @error('name')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Username <span class="text-red-500">*</span></label>
        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 @error('username') border-red-500 @enderror">
        @error('username')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 @error('email') border-red-500 @enderror">
        @error('email')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Role <span class="text-red-500">*</span></label>
        <select name="role" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 @error('role') border-red-500 @enderror">
            <option value="staff" @selected(old('role', $user->role ?? '') == 'staff')>Staff</option>
            <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>Admin</option>
        </select>
        @error('role')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }} <span class="{{ isset($user) ? '' : 'text-red-500' }}">{{ isset($user) ? '' : '*' }}</span></label>
        <input type="password" name="password" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 @error('password') border-red-500 @enderror">
        @error('password')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Konfirmasi Password {{ isset($user) ? '' : '*' }}</label>
        <input type="password" name="password_confirmation" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2">
    </div>
</div>
