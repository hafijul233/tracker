<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <!-- meta Tags -->
@include('admin::Core.Resources.views.layouts.includes.meta')
<!-- Web Font-->
@include('admin::Core.Resources.views.layouts.includes.webfont')
<!-- Icon -->
@include('admin::Core.Resources.views.layouts.includes.icon')
<!-- Plugins -->
@include('admin::Core.Resources.views.layouts.includes.plugin-style')
<!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <!-- Page Level Style -->
@include('admin::Core.Resources.views.layouts.includes.inline-style')
<!-- Page Level Script -->
    @include('admin::Core.Resources.views.layouts.includes.head-script')
</head>
<body class="hold-transition @yield('body-class')">
<div class="wrapper">
    <!-- Preloader -->
@include('admin::Core.Resources.views.layouts.includes.preloader')
<!-- Navbar -->
@include('admin::Core.Resources.views.layouts.partials.navbar')

<!-- Main Sidebar Container -->
@include('admin::Core.Resources.views.layouts.partials.menu-sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    @include('admin::Core.Resources.views.layouts.partials.content-header')
    <!-- Main content -->
        <section class="content">

            <div class="error-page">
                <h2 class="headline text-@yield('text-color', 'danger')">@yield('code')</h2>

                <div class="error-content">
                    <h3>
                        <i class="fas fa-exclamation-triangle text-@yield('text-color', 'danger')"></i> @yield('message')
                    </h3>

                    <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search
                        form.
                    </p>

                    {!! \Form::open(['link' => '#', 'method' => 'get', 'class' => 'search-form']) !!}
                    <div class="input-group">
                        {!! \Form::search('search', \request()->query('search'),
                                ['class' => 'form-control', 'id' => 'search-from',
                                'placeholder' => 'Type you search ...']) !!}
                        <div class="input-group-append">
                            <button type="submit" name="submit" class="btn btn-warning">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.input-group -->
                    {!! \Form::close() !!}
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
@include('admin::Core.Resources.views.layouts.partials.control-sidebar')
<!-- Main Footer -->
    @include('admin::Core.Resources.views.layouts.partials.main-footer')
</div>
<!-- ./wrapper -->
<!-- JS Constants -->
@include('admin::Core.Resources.views.layouts.includes.js-constants')
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Plugin JS -->
@include('admin::Core.Resources.views.layouts.includes.plugin-script')
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/utility.min.js') }}"></script>
<!-- inline js -->
@include('admin::Core.Resources.views.layouts.includes.page-script')
</body>
</html>
