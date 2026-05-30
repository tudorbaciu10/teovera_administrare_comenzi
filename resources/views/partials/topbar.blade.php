<header class="topbar" id="topbar">
    <span class="topbar-title topbar-page-title" id="topbarTitle">@yield('page-title', '')</span>

    <div class="topbar-right" id="topbarRight">

        {{-- Comutator limbă --}}
        <div class="lang-switch topbar-lang-switch" id="langSwitch">
            <a href="{{ route('lang.switch', 'ro') }}"
               class="lang-switch-btn lang-switch-btn--ro {{ app()->getLocale() === 'ro' ? 'active' : '' }}">RO</a>
            <span class="lang-switch-separator">|</span>
            <a href="{{ route('lang.switch', 'ru') }}"
               class="lang-switch-btn lang-switch-btn--ru {{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
        </div>

        {{-- Utilizator --}}
        <span class="topbar-user topbar-user-info" id="topbarUser">
            {{ __('admin.salut') }}, <strong class="topbar-user-name">{{ auth()->user()->name }}</strong>
        </span>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form" id="formLogout" style="display:inline">
            @csrf
            <button type="submit" class="btn-secondary btn-sm btn-logout">{{ __('admin.deconectare') }}</button>
        </form>

    </div>
</header>
