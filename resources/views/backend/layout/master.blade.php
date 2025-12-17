<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Accounting Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Flat Management System" name="description" />
    <meta content="Myra Studio" name="author" />

    <!-- App favicon -->
    @include('backend.layout.style')
</head>

<body>

<!-- Begin page -->
<div class="layout-wrapper">

    <!-- ========== Left Sidebar ========== -->
    <div class="main-menu">

        <!-- Brand Logo -->
        <div class="logo-box">
            <!-- Brand Logo Light -->
            <a href="{{url('/dashboard')}}" class="logo-light">
                <img src="{{asset('backend/images/logo.png')}}" alt="logo" class="logo-lg" height="28">
                <img src="{{asset('backend/images/logo.png')}}" alt="small logo" class="logo-sm" height="28">
            </a>

            <!-- Brand Logo Dark -->
            <a href="{{url('/dashboard')}}" class="logo-dark">
                <img src="{{asset('backend/images/logo.png')}}" alt="dark logo" class="logo-lg" height="28">
                <img src="{{asset('backend/images/logo.png')}}" alt="small logo" class="logo-sm" height="28">
            </a>
        </div>

        <!--- Menu -->
        @include('backend.layout.sidebar')

    </div>



    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">
        @include('backend.layout.header')
        @yield('content')
    </div>
</div>
<!-- END wrapper -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div>
                    <script>document.write(new Date().getFullYear())</script>
                    © Fanush Soft
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                    <p class="mb-0">Design & Develop by <a href="https://fanushsoft.com/"
                                                           target="_blank">Fanush Soft</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- App js -->
@include('backend.layout.script')
@yield('script')

</body>

</html>
