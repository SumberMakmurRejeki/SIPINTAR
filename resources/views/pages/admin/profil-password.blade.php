@extends('layouts.admin', ['title' => 'Profil & Password'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Profil & Password" subtitle="Kelola profil dan password akun Anda." />

        {{-- Account Info --}}
        <x-ui.form-section title="Informasi Akun">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-[13px] font-medium text-[#64748b]">Username</p>
                    <p class="text-[14px] text-[#0f172a]">{{ $user->username }}</p>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-[#64748b]">Role</p>
                    <p class="text-[14px] text-[#0f172a]">{{ ucfirst($user->role) }}</p>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-[#64748b]">Status</p>
                    <p class="text-[14px] text-[#0f172a]">
                        <x-ui.badge :variant="$user->status === 'active' ? 'success' : 'danger'">
                            {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </x-ui.badge>
                    </p>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-[#64748b]">Login Terakhir</p>
                    <p class="text-[14px] text-[#0f172a]">{{ $user->last_login_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </x-ui.form-section>

        {{-- Update Profile Form --}}
        <x-ui.form-section title="Update Profil" description="Ubah nama dan email akun Anda.">
            <form method="POST" action="{{ route('admin.profil-password.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-ui.input name="name" label="Nama" :value="$user->name" required />
                <x-ui.input name="email" label="Email" type="email" :value="$user->email" placeholder="opsional" />

                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="primary">Simpan Profil</x-ui.button>
                </div>
            </form>
        </x-ui.form-section>

        {{-- Change Password Form --}}
        <x-ui.form-section title="Ubah Password" description="Ganti password akun Anda.">
            <form method="POST" action="{{ route('admin.profil-password.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-ui.input name="current_password" label="Password Lama" type="password" required />
                <x-ui.input name="password" label="Password Baru" type="password" required helper="Minimal 8 karakter" />
                <x-ui.input name="password_confirmation" label="Konfirmasi Password Baru" type="password" required />

                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="primary">Ubah Password</x-ui.button>
                </div>
            </form>
        </x-ui.form-section>
    </div>
@endsection
