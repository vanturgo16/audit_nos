<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/LOGO_MSK_PUTIH_VERTIKAL.jpg') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logosamping.png') }}" alt="" height="50">
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/LOGO_MSK_MERAH_VERTIKAL.jpg') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logosamping.png') }}" alt="" height="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- Role-->
            @if(Auth::check() && Auth::user()->role == null)
                {{-- Code to destroy authentication session --}}
                <?php Auth::logout(); ?>
            @endif
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <h3 class="d-inline-block me-2"><span class="badge bg-danger text-white">{{ Auth::user()->role }}</span></h3>
                </div>
            </form>
        </div>

        <div class="d-flex">
                                
            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" data-bs-toggle="modal" data-bs-target="#switchTheme">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                @php use Illuminate\Support\Facades\File; $defaultImagePath = public_path('assets/images/users/userbg.png'); @endphp
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->photo_path)
                        @php $userImagePath = public_path(Auth::user()->photo_path); @endphp
                        @if(File::exists($userImagePath))
                            <img class="rounded-circle header-profile-user" src="{{ asset(Auth::user()->photo_path) }}" alt="Header Avatar">
                        @else
                            @if(File::exists($defaultImagePath))
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userbg.png') }}" alt="Header Avatar">
                            @endif
                        @endif
                    @else 
                        @if(File::exists($defaultImagePath))
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userbg.png') }}" alt="Header Avatar">
                        @endif
                    @endif
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span> <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Modal Switch Them -->
<div class="modal fade" id="switchTheme" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Switch Theme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h5>Swith To <b>@if(Auth::user()->is_darkmode) Light @else Dark @endif</b> Mode ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <form class="formLoad" action="{{ route('switchTheme') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check label-icon"></i>Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Logout -->
<div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select "Logout" below if you are ready to end your current session.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <form class="formLoad" action="{{ route('logout') }}" id="formlogout" method="POST" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-logout label-icon"></i>Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>