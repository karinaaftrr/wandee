@php
    $currentRoute = Route::currentRouteName();
@endphp
<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="admin-logo" style="text-decoration:none;">
        Wandee<span style="color:#fff;">.</span>
    </a>

    <nav class="admin-menu">
        <a href="{{ route('admin.dashboard') }}" class="{{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.manage') }}" class="{{ in_array($currentRoute, ['admin.manage', 'admin.edit_destination']) ? 'active' : '' }}">
            <i data-lucide="map"></i>
            <span>Manage Destinasi</span>
        </a>

        <a href="{{ route('admin.manage_payments') }}" class="{{ $currentRoute === 'admin.manage_payments' ? 'active' : '' }}">
            <i data-lucide="credit-card"></i>
            <span>Verifikasi Pembayaran</span>
        </a>

        <a href="{{ route('admin.manage_reviews') }}" class="{{ $currentRoute === 'admin.manage_reviews' ? 'active' : '' }}">
            <i data-lucide="message-square"></i>
            <span>Manage Ulasan</span>
        </a>

        <a href="{{ route('admin.profile') }}" class="{{ $currentRoute === 'admin.profile' ? 'active' : '' }}">
            <i data-lucide="user"></i>
            <span>Profil Saya</span>
        </a>

        <a href="#" data-logout-link>
            <i data-lucide="log-out"></i>
            <span>Keluar</span>
        </a>
    </nav>
</aside>
