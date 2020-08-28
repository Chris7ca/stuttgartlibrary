<header class="uk-container uk-container-large">
    <nav class="uk-navbar-container uk-navbar-transparent uk-position-relative" uk-navbar="mode: click">

        <div class="uk-navbar-left">
    
            <ul class="uk-navbar-nav">
                <li>
                    <a href="{{ route('home') }}">
                        <img width="150" src="{{ asset('images/stuttgart-logo.png') }}" alt="Stuttgart Logo">
                    </a>
                </li>
                <li class="uk-hidden@s">
                    <a role="button">Menu</a>
                    <div class="uk-navbar-dropdown uk-width-medium">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                            <li class="{{ request()->path() == '/' ? 'uk-active' : '' }}">
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="{{ Str::startsWith(request()->path(), 'library') ? 'uk-active' : '' }}">
                                <a href="{{ route('library.view') }}">Library</a>
                            </li>
                            @auth
                                @if (Auth::user()->presenter()->isLibrarian())
                                    <li class="{{ Str::startsWith(request()->path(), 'admin') ? 'uk-active' : '' }}">
                                        <a href="{{ route('admin.view') }}">Admin</a>
                                    </li>
                                @else
                                    <li class="{{ Str::startsWith(request()->path(), 'mybooks') ? 'uk-active' : '' }}">
                                        <a href="{{ route('admin.users.books.view') }}">My books</a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </li>
                <li class="uk-visible@s {{ request()->path() == '/' ? 'uk-active' : '' }}">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="uk-visible@s {{ Str::startsWith(request()->path(), 'library') ? 'uk-active' : '' }}">
                    <a href="{{ route('library.view') }}">Library</a>
                </li>
                @auth
                    @if (Auth::user()->presenter()->isLibrarian())
                        <li class="uk-visible@s {{ Str::startsWith(request()->path(), 'admin') ? 'uk-active' : '' }}">
                            <a href="{{ route('admin.view') }}">Admin</a>
                        </li>
                    @else
                        <li class="uk-visible@s {{ Str::startsWith(request()->path(), 'mybooks') ? 'uk-active' : '' }}">
                            <a href="{{ route('admin.users.books.view') }}">My books</a>
                        </li>
                    @endif
                @endauth
            </ul>
    
        </div>
    
        <div class="uk-navbar-right">
    
            <ul class="nav-overlay uk-navbar-nav">
                @if ( request()->path() == '/' )
                    <li>
                        <a class="uk-navbar-toggle" uk-search-icon uk-toggle="target: .nav-overlay; animation: uk-animation-fade" role="button"></a>
                    </li>
                @endif
                @auth
                    <li>
                        <a role="button">
                            <img width="40px" height="40px" src="{{ asset('images/avatar.png') }}" class="uk-avatar-without-image uk-border-rounded uk-box-shadow-medium" data-name="{{ Auth::user()->name }}">
                        </a>
                        <div class="uk-navbar-dropdown uk-width-medium">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li class="uk-nav-header uk-text-bold uk-margin-small">
                                    {{ Auth::user()->presenter()->userName() }}
                                </li>
                                <li>
                                    <a role="button" onclick="document.getElementById('logout-form').submit();">
                                        <span class="uk-margin-small-right" uk-icon="sign-out"></span> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login.view') }}">Login</a>
                    </li>
                @endauth
            </ul>
    
        </div>

        @if ( request()->path() == '/' )
            <div class="nav-overlay uk-navbar-left uk-flex-1 uk-nav-search-bar" hidden>
                <search-bar :id="'search-bar-navbar'" class="uk-width-1-1"></search-bar>        
            </div>
        @endif
    
    </nav>
</header>