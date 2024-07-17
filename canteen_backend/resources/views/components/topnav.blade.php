<nav class="bottom-navbar">
    <div class="container">
        <ul class="nav page-navigation">
            <li class="nav-item ">
                <a class="nav-link" href="{{route('home')}}">
                    <i class="mdi mdi-file-document-box menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <!-- <li class="nav-item {{ request()->routeIs('inventory') || request()->routeIs('cashier') ? 'active' : '' }}"> -->
            <li class="nav-item">
                <a href="{{route('meal')}}" class="nav-link">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Meal List</span>
                    <i class="menu-arrow"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('order')}}" class="nav-link">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Order List</span>
                    <i class="menu-arrow"></i>
                </a>
            </li>

            <!-- commented  -->

            {{--
            <li class="nav-item {{ request()->routeIs('room') || request()->routeIs('pages.room.show') ? 'active' : '' }}">
            <a href="{{route('room')}}" class="nav-link">
                <i class="mdi mdi-store menu-icon"></i>
                <span class="menu-title">ClassRooms</span>
                <i class="menu-arrow"></i>
            </a>
            </li>
            @if (auth()->user()->role == 'teacher')
            <li class="nav-item">
                <a href="{{route('exam')}}" class="nav-link">
                    <i class="mdi mdi-account-network menu-icon"></i>
                    <span class="menu-title">Exam Builder</span>
                    <i class="menu-arrow"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('class')}}" class="nav-link">
                    <i class="mdi mdi-account-network menu-icon"></i>
                    <span class="menu-title">Class</span>
                    <i class="menu-arrow"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('users')}}" class="nav-link">
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                    <span class="menu-title">Users</span>
                    <i class="menu-arrow"></i>
                </a>
            </li>
            @endif
            --}}

        </ul>
    </div>
</nav>