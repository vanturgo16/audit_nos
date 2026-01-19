<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard NOS | AHASS BANTEN</title>
    <title>-</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- plugin css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" />
    <!-- Responsive datatable -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />
    <!-- preloader css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/preloader.min.css') }}" />
    <!-- Bootstrap Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" />
    <!-- Icons Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/@mdi/css/materialdesignicons.min.css') }}" />
    <!-- App Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.min.css') }}" id="app-style" />
    <!-- Custom Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" id="app-style" />
    <!-- Choices Select Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/css/select2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/css/select2.min.css') }}" />

    <!-- Jquery-->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- SheetJS -->
    <script src="{{ asset('assets/libs/sheetJs/xlsx.full.min.js') }}"></script>
    <!-- Highchart-->
    <script src="{{ asset('assets/libs/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/libs/highcharts/highcharts-more.js') }}"></script>
    <script src="{{ asset('assets/libs/highcharts/modules/exporting.js') }}"></script>
    <script src="{{ asset('assets/libs/highcharts/modules/export-data.js') }}"></script>
    <script src="{{ asset('assets/libs/highcharts/modules/accessibility.js') }}"></script>
</head>

<body @if (Auth::user()->is_darkmode) data-bs-theme="dark" data-topbar="dark" data-sidebar="dark" @endif>
    @include('layouts.loading')
    @include('layouts.toast')
    
    <!-- General Modal -->
    @include('layouts.modal.dynamic')

    <!-- ========== BEGIN page ========== -->
    <div id="layout-wrapper">
        <!-- Left Sidebar -->
        @include('layouts.sidebar')

        <!-- start main content-->
        <div class="main-content bg-company">
            @include('layouts.header')
            @yield('konten')
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>

    <!-- Right Sidebar -->
    @include('layouts.rightsidebar')
    
    <!-- Scroll To Top Button -->
    <button type="button" class="btn btn-primary rounded-circle shadow scroll-to-top" id="scrollTopBtn" style="display: none;">
        <i class="mdi mdi-arrow-up"></i>
    </button>

    <!-- ========== JAVASCRIPT ========== -->
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
    <!-- Choices Select js -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.init.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <!-- ckeditor -->
    <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <!-- Sidebar Scroll as Link -->
    <script src="{{ asset('assets/js/sidebarFocus.js') }}"></script>
    <!-- Collapse Card -->
    <script src="{{ asset('assets/js/collapse.js') }}"></script>
    <!-- Custom -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/formLoad.js') }}"></script>
</body>

</html>