@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Pengguna Sistem')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Manajemen User</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Daftar Akun Pengguna</h2>
        <p class="page-subtitle">Kelola akun dan role pengguna Sistem Arsip PTPN4</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Tambah User Baru
    </a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Tanggal Terdaftar</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #16a34a, #4ade80); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                {{ strtoupper(substr($u->nama, 0, 2)) }}
                            </div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #0f172a;">{{ $u->nama }}</div>
                        </div>
                    </td>
                    <td><span style="font-family: monospace; color: #475569;">{{ $u->username }}</span></td>
                    <td>
                        @php
                            $roleColor = match($u->role) {
                                'Administrator' => 'badge-blue',
                                'Admin Gudang'  => 'badge-green',
                                'Kepala Gudang' => 'badge-purple',
                                default => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $roleColor }}">{{ $u->role }}</span>
                    </td>
                    <td style="font-size: 0.85rem; color: #64748b;">{{ $u->created_at->format('d M Y') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 0.375rem;">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-icon btn-info btn-xs" title="Edit">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if(auth()->id() !== $u->id)
                            <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus user {{ $u->username }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-xs" title="Hapus">
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Tidak ada data user.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9;">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@endsection
