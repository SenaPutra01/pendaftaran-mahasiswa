<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">IDS</a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Menu Dashboard untuk semua role --}}
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Menu untuk Administrator --}}
            @if (auth()->user()->role === 'administrator')
            <li class="sidebar-menu-group-title">Academic</li>
            <li>
                <a href="{{ route('admin.fakultas.index') }}">
                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                    Fakultas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.program-studi.index') }}">
                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                    Program Studi
                </a>
            </li>

            <li class="sidebar-menu-group-title">Reports</li>
            {{-- <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Invoice</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="invoice-list.html">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            List
                        </a>
                    </li>
                </ul>
            </li> --}}

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Data Pendaftar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.calon-mahasiswa.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            List
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-group-title">Manage Users</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Users List
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Menu untuk Mahasiswa --}}
            @if (auth()->user()->role === 'mahasiswa')
            <li class="sidebar-menu-group-title">Pendaftaran</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Alur Pendaftaran</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('pendaftaran.data-diri') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Data Diri
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pendaftaran.pilih-prodi') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Pilih Program Studi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pendaftaran.pembayaran') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Pilih Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pendaftaran.pembayaran') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Status Pendaftaran
                        </a>
                    </li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
</aside>