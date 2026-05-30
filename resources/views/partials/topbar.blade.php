<header class="topbar">
    <span class="topbar-title">@yield('page-title', '')</span>

    <div class="topbar-right">

        {{-- Comutator limbă --}}
        <div class="lang-switch">
            <a href="{{ route('lang.switch', 'ro') }}"
               class="{{ app()->getLocale() === 'ro' ? 'active' : '' }}">RO</a>
            <span>|</span>
            <a href="{{ route('lang.switch', 'ru') }}"
               class="{{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
        </div>

        {{-- Utilizator --}}
        <span class="topbar-user">
            {{ __('admin.salut') }}, <strong>{{ auth()->user()->name }}</strong>
        </span>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-secondary btn-sm">{{ __('admin.deconectare') }}</button>
        </form>

    </div>
</header>
