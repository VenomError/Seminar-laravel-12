<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ auth()->user()?->role?->pathRedirect() ?? '' }}" class="logo logo-normal">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="{{ auth()->user()?->role?->pathRedirect() ?? '' }}" class="logo-small">
                <img src="{{ asset('assets/img/logo-small.svg') }}" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="{{ auth()->user()?->role?->pathRedirect() ?? '' }}" class="dark-logo">
                <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-left"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->
    @php
        $config = auth()->user()?->role?->sidenavConfig() ?? '';
        $sidenavs = config($config, []);
    @endphp

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @foreach ($sidenavs as $title => $navs)
                    <li class="menu-title"><span>{{ str($title)->title() }}</span></li>
                    <li>
                        <ul>

                            @foreach ($navs as $nav)
                                @if (isset($nav['sub']) && !empty($nav['sub']))
                                    <li class="submenu">
                                        <a href="{{ route($nav['href']) }}" wire:current="active subdrop">
                                            <i class="{{ $nav['icon'] }}"></i><span>{{ str($nav['title'])->title() }}</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <ul>
                                            @foreach ($nav['sub'] as $sub)
                                                <li><a href="{{ route($sub['href']) }}"
                                                        wire:current.strict="active">{{ str($sub['title'])->title() }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route($nav['href']) }}" wire:current="active">
                                            <i class="{{ $nav['icon'] }}"></i><span>{{ str($nav['title'])->title() }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

</div>