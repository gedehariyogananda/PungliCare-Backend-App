<header>
    <nav class="navbar navbar-expand navbar-light">
        <div class="container-fluid">
            <div class="w-100 mt-4 ">
                <span class="text-sm breadcrumb-item-bold">Halaman / {{ $pages }}</span>
                <h2 class="welcome-text">{{ $pagesInit }}</h2>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 card custom-card p-1">
                    <li class="nav-item dropdown">
                        <a class="nav-link active" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bi bi-bell bi-sub fs-5 text-secondary'></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>
                            <li><a class="dropdown-item">No notification available</a></li>
                        </ul>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link active" href="#">
                            <i class='bi bi-info-circle bi-sub fs-5 text-gray-600'></i>
                        </a>
                    </li>
                    <div class="dropdown mt-1">
                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <img style="width: 200%;" src="{{ asset('storage/profile/poldajatim.png') }}"
                                            alt="adwwadw">
                                    </div>
                                </div>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu"
                            aria-labelledby="dropdownMenuButton">
                            <li>
                                <h6 class="dropdown-header">Hello, {{ strtok(Auth::user()->nama, " ") }}!</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                {{-- <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form> --}}
                            </li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
</header>

<style>
    .breadcrumb-item-bold {
        font-weight: normal;
        color: #707EAE;
    }

    .welcome-text {
        font-size: 25px;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 10px;
        /* Adjust these values as needed */
    }

    .custom-card {
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .custom-card .nav-link i {
        font-size: 1rem;
        /* Adjust the icon size */
    }

    .custom-card .user-menu .avatar img {
        width: 35px;
        /* Adjust the avatar size */
        height: 35px;
        border-radius: 50%;
    }

    .custom-dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .custom-dropdown-menu .card-body {
        padding: 15px;
    }
</style>