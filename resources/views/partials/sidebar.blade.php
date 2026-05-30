<aside class="sidebar">

    <div class="sidebar-header">
        <span class="sidebar-brand">Avicola-Teovera</span>
        <span class="sidebar-sub">{{ auth()->user()->name }}</span>
    </div>

    <nav class="sidebar-nav">

        {{-- PRINCIPAL --}}
        <div class="sidebar-section">{{ __('admin.nav_principal') }}</div>

        <a href="{{ route('orders.index') }}"
           class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            {{ __('admin.nav_comenzi_lista') }}
        </a>

        @if(auth()->user()->isAdmin())
            {{-- CATALOG --}}
            <div class="sidebar-section">{{ __('admin.nav_catalog') }}</div>

            <a href="{{ route('products.index') }}"
               class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                {{ __('admin.nav_produse') }}
            </a>

            <a href="{{ route('categories.index') }}"
               class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                {{ __('admin.nav_categorii') }}
            </a>

            {{-- ADMINISTRARE --}}
            <div class="sidebar-section">{{ __('admin.nav_administrare') }}</div>

            <a href="{{ route('routes.index') }}"
               class="sidebar-link {{ request()->routeIs('routes.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                {{ __('admin.nav_rute') }}
            </a>

            <a href="{{ route('stores.index') }}"
               class="sidebar-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                {{ __('admin.nav_magazine') }}
            </a>

            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ __('admin.nav_utilizatori') }}
            </a>
        @endif

    </nav>

</aside>
