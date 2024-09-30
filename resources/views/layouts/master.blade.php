<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard NOS | AHASS BANTEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 
    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- choices css -->
    <link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- Custom --}}
    <link href="{{ asset('assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom2.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.10.0/full-all/ckeditor.js"></script>
    
    {{-- select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    {{-- Highchart --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>

<body>
<!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
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
                            <h3 class="d-inline-block me-2"><span class="badge bg-info text-white">{{ Auth::user()->role }}</span></h3>
                            <h5 class="d-inline-block"> | Form Checklist H1 Premises ( Quartal III )</h5>
                        </div>
                    </form>
                </div>

                <div class="d-flex">

                    <div class="dropdown d-none d-sm-inline-block">
                        <button type="button" class="btn header-item" id="mode-setting-btn">
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
                        <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userbg.png') }}"
                                alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->

                    {{-- Super Admin --}}
                    @if(Auth::user()->role == 'Super Admin')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>
                            <li class="menu-title" data-key="t-menu">Configuration</li>
                            <li>
                                <a href="{{ route('user.index') }}">
                                    <i data-feather="users"></i>
                                    <span>Manage User</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Master Data</li>

                            <li>
                                <a href="{{ route('employee.index') }}">
                                    <i class="mdi mdi-account-group"></i>
                                    <span>Master Employee</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('department.index') }}">
                                    <i class="mdi mdi-graph-outline"></i>
                                    <span>Master Dept</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('position.index') }}">
                                    <i class="mdi mdi-lan"></i>
                                    <span>Master Position</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jaringan.index') }}">
                                    <i class="mdi mdi-office-building"></i>
                                    <span>Master Jaringan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rule.index') }}">
                                    <i class="mdi mdi-cog-box"></i>
                                    <span>Master Rule</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dropdown.index') }}">
                                    <i class="mdi mdi-package-down"></i>
                                    <span>Master Dropdown</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('parentchecklist.typechecklist') }}">
                                    <i class="mdi mdi-clipboard-check-multiple"></i>
                                    <span>Master Parent Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('checklist.typechecklist') }}">
                                    <i class="mdi mdi-check-network"></i>
                                    <span>Master Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mapchecklist.index') }}">
                                    <i class="mdi mdi-checkbox-multiple-outline"></i>
                                    <span>Master Mapping Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('grading.index') }}">
                                    <i class="mdi mdi-percent-outline"></i>
                                    <span>Master Grading</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Checklist Audit</li>
                            <li>
                                <a href="{{ route('periodname.index') }}">
                                    <i class="mdi mdi-clipboard-text-clock"></i>
                                    <span>Master Period Name</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('periodchecklist.index') }}">
                                    <i class="mdi mdi-check-underline-circle"></i>
                                    <span>Period Checklist</span>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('formchecklist.form') }}">
                                    <i class="mdi mdi-check-underline-circle"></i>
                                    <span>Contoh Form Checklist</span>
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{ route('formchecklist.index') }}">
                                    <i class="mdi mdi-file-check"></i>
                                    <span>Form Checklist</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Assessor Menu</li>
                            <li>
                                <a href="{{ route('assessor.listjaringan') }}">
                                    <i class="mdi mdi-check-underline-circle"></i>
                                    <span>Checklist</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Logs</li>
                            <li>
                                <a href="{{ route('auditlog') }}">
                                    <i class="mdi mdi-chart-donut"></i>
                                    <span>Audit Logs</span>
                                </a>
                            </li>

                        </ul>
                    @endif

                    {{-- Admin --}}
                    @if(Auth::user()->role == 'Admin')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>
                            <li class="menu-title" data-key="t-menu">Configuration</li>
                            <li>
                                <a href="{{ route('user.index') }}">
                                    <i data-feather="users"></i>
                                    <span>Manage User</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Master Data</li>

                            <li>
                                <a href="{{ route('employee.index') }}">
                                    <i class="mdi mdi-account-group"></i>
                                    <span>Master Employee</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('department.index') }}">
                                    <i class="mdi mdi-graph-outline"></i>
                                    <span>Master Dept</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('position.index') }}">
                                    <i class="mdi mdi-lan"></i>
                                    <span>Master Position</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jaringan.index') }}">
                                    <i class="mdi mdi-office-building"></i>
                                    <span>Master Jaringan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dropdown.index') }}">
                                    <i class="mdi mdi-package-down"></i>
                                    <span>Master Dropdown</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('parentchecklist.typechecklist') }}">
                                    <i class="mdi mdi-clipboard-check-multiple"></i>
                                    <span>Master Parent Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('checklist.typechecklist') }}">
                                    <i class="mdi mdi-check-network"></i>
                                    <span>Master Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mapchecklist.index') }}">
                                    <i class="mdi mdi-checkbox-multiple-outline"></i>
                                    <span>Master Mapping Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('grading.index') }}">
                                    <i class="mdi mdi-percent-outline"></i>
                                    <span>Master Grading</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Logs</li>
                            <li>
                                <a href="{{ route('auditlog') }}">
                                    <i class="mdi mdi-chart-donut"></i>
                                    <span>Audit Logs</span>
                                </a>
                            </li>

                        </ul>
                    @endif

                    {{-- PIC Dealers --}}
                    @if(Auth::user()->role == 'PIC Dealers')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Master Data</li>
                            <li>
                                <a href="{{ route('jaringan.index') }}">
                                    <i class="mdi mdi-office-building"></i>
                                    <span>Master Jaringan</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('parentchecklist.typechecklist') }}">
                                    <i class="mdi mdi-clipboard-check-multiple"></i>
                                    <span>Master Parent Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('checklist.typechecklist') }}">
                                    <i class="mdi mdi-check-network"></i>
                                    <span>Master Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mapchecklist.index') }}">
                                    <i class="mdi mdi-checkbox-multiple-outline"></i>
                                    <span>Master Mapping Checklist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('grading.index') }}">
                                    <i class="mdi mdi-percent-outline"></i>
                                    <span>Master Grading</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('periodname.index') }}">
                                    <i class="mdi mdi-clipboard-text-clock"></i>
                                    <span>Master Period Name</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Assign Checklist</li>
                            <li>
                                <a href="{{ route('periodchecklist.index') }}">
                                    <i class="mdi mdi-check-underline-circle"></i>
                                    <span>Assign Period Checklist</span>
                                </a>
                            </li>

                        </ul>
                    @endif

                    {{-- Assessor Main Dealer --}}
                    @if(Auth::user()->role == 'Assessor Main Dealer')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Assessor Menu</li>
                            <li>
                                <a href="{{ route('assessor.listjaringan') }}">
                                    <i class="mdi mdi-clipboard-check"></i>
                                    <span>Result Checklist</span>
                                </a>
                            </li>

                        </ul>
                    @endif

                    {{-- PIC NOS MD --}}
                    @if(Auth::user()->role == 'PIC NOS MD')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">PIC MD Menu</li>
                            <li>
                                <a href="{{ route('assessor.listjaringan') }}">
                                    <i class="mdi mdi-clipboard-check"></i>
                                    <span>Result Checklist</span>
                                </a>
                            </li>

                        </ul>
                    @endif

                    {{-- Internal Auditor Dealer --}}
                    @if(Auth::user()->role == 'Internal Auditor Dealer')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Auditor Menu</li>
                            <li>
                                <a href="{{ route('formchecklist.auditor') }}">
                                    <i class="mdi mdi-file-check"></i>
                                    <span>Form Checklist</span>
                                </a>
                            </li>

                        </ul>
                    @endif
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Start Page-content -->
            @yield('konten')
            <!-- End Page-content -->


            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            {{-- © Dashboard NOS Honda Banten 2024 --}}
                            © Dashboard NOS PT Mitra Sendang Kemakmuran Banten 2024
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

        {{-- Modal Logout --}}
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
                        <form action="{{ route('logout') }}" id="formlogout" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-logout label-icon"></i>Logout</button>
                        </form>
                        <script>
                            document.getElementById('formlogout').addEventListener('submit', function(event) {
                                if (!this.checkValidity()) {
                                    event.preventDefault(); // Prevent form submission if it's not valid
                                    return false;
                                }
                                var submitButton = this.querySelector('button[name="sb"]');
                                submitButton.disabled = true;
                                submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                return true; // Allow form submission
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END layout-wrapper -->

    
    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center p-3">

                <h5 class="m-0 me-2">Theme Customizer</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>

            <!-- Settings -->
            <hr class="m-0" />

            <div class="p-4">
                <h6 class="mb-3">Layout</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout"
                        id="layout-vertical" value="vertical">
                    <label class="form-check-label" for="layout-vertical">Vertical</label>
                </div>
                <div class="form-check form-check-inline">
                    {{-- <input class="form-check-input" type="radio" name="layout"
                        id="layout-horizontal" value="horizontal">
                    <label class="form-check-label" for="layout-horizontal">Horizontal</label> --}}
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-light" value="light">
                    <label class="form-check-label" for="layout-mode-light">Light</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-dark" value="dark">
                    <label class="form-check-label" for="layout-mode-dark">Dark</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                    <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                    <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                    <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                    <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                    <label class="form-check-label" for="topbar-color-light">Light</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                    <label class="form-check-label" for="sidebar-size-default">Default</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                    <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                    <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                    <label class="form-check-label" for="sidebar-color-light">Light</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                    <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                    <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Direction</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-ltr" value="ltr">
                    <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                </div>
                <div class="form-check form-check-inline">
                    {{-- <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-rtl" value="rtl">
                    <label class="form-check-label" for="layout-direction-rtl">RTL</label> --}}
                </div>

            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- choices js -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <!-- Custom -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
</body>

</html>