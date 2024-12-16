<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ardhana Putra Lestari</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @can('read-project')
                <li class="nav-item">
                    <a href="{{ route('project.index') }}"
                        class="nav-link {{ request()->is('project*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Kegiatan
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-vendor')
                <li class="nav-item">
                    <a href="{{ route('vendor.index') }}" class="nav-link {{ request()->is('vendor*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Vendor
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-tukang')
                <li class="nav-item">
                    <a href="{{ route('tukang.index') }}"
                        class="nav-link {{ request()->is('tukang*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Worker/Tukang
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-customer')
                <li class="nav-item">
                    <a href="{{ route('customer.index') }}"
                        class="nav-link {{ request()->is('customer*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Customer
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-material')
                <li class="nav-item {{ request()->is('material*') || request()->is('transaction*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('material*') || request()->is('material*')  ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Material
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('material.index') }}" class="nav-link {{ request()->is('material*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transaction.index') }}" class="nav-link {{ request()->is('transaction*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transaksi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('read-bahan')
                <li class="nav-header">MASTER BAHAN</li>
                @endcan
                @can('read-bahan')
                <li class="nav-item">
                    <a href="{{ route('bahan.index') }}"
                        class="nav-link {{ request()->is('bahan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>
                            Bahan
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-satuan')
                <li class="nav-item">
                    <a href="{{ route('satuan.index') }}"
                        class="nav-link {{ request()->is('satuan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            Satuan
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-bahan')
                <li class="nav-header">DATA PENGGUNA</li>
                @can('read-users')
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>
                @endcan
                @endcan

                @can(['read-roles', 'read-permissions'])
                <li class="nav-header">ACCESS & PERMISSIONS</li>
                @endcan
                @can('read-roles')
                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link {{ request()->is('role*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Role
                        </p>
                    </a>
                </li>
                @endcan
                @can('read-permissions')
                <li class="nav-item">
                    <a href="{{ route('permission.index') }}"
                        class="nav-link {{ request()->is('permission*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Permission
                        </p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </form>

                </li>
            </ul>
        </nav>

    </div>

</aside>