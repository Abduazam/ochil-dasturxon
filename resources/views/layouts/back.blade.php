<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>@yield('title')</title>
    <meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="/backend/assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/backend/assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/backend/assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="/backend/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="/backend/assets/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/backend/assets/js/plugins/simplemde/simplemde.min.css">
    <link rel="stylesheet" href="/backend/assets/js/plugins/flatpickr/flatpickr.min.css">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/backend/assets/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="/backend/assets/css/codebase.min.css">
</head>
<body>

    <div id="page-container" class="sidebar-o sidebar-inverse enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">
        <nav id="sidebar">
            <div class="sidebar-content">
                <!-- Side Header -->
                <div class="content-header content-header-fullrow px-15">
                    <!-- Mini Mode -->
                    <div class="content-header-section sidebar-mini-visible-b">
                        <!-- Logo -->
                        <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                            <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                        </span>
                    </div>

                    <!-- Normal Mode -->
                    <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                        <!-- Close Sidebar, Visible only on mobile screens -->
                        <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                            <i class="fa fa-times text-danger"></i>
                        </button>
                        <!-- END Close Sidebar -->

                        <!-- Logo -->
                        <div class="content-header-item">
                            <a class="link-effect font-w700" href="{{ url('/') }}">
                                <i class="si si-fire text-primary"></i>
                                <span class="font-size-xl text-dual-primary-dark">code</span><span class="font-size-xl text-primary">base</span>
                            </a>
                        </div>
                        <!-- END Logo -->
                    </div>
                </div>

                <!-- Sidebar Scrolling -->
                <div class="js-sidebar-scroll">
                    <!-- Side User -->
                    <div class="content-side content-side-full content-side-user px-10 align-parent">
                        <!-- Visible only in mini mode -->
                        <div class="sidebar-mini-visible-b align-v animated fadeIn">
                            <img class="img-avatar img-avatar32" src="/backend/assets/media/avatars/avatar15.jpg" alt="">
                        </div>

                        <!-- Visible only in normal mode -->
                        <div class="sidebar-mini-hidden-b text-center">
                            <a class="img-link" href="{{ url('/') }}">
                                <img class="img-avatar" src="/backend/assets/media/avatars/avatar15.jpg" alt="">
                            </a>
                            <ul class="list-inline mt-10">
                                <li class="list-inline-item">
                                    <p class="link-effect text-dual-primary-dark font-size-sm font-w600 text-uppercase">Admin</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END Side User -->

                    <!-- Side Navigation -->
                    <div class="content-side content-side-full">
                        <ul class="nav-main">
                            <li>
                                <a href="{{ url('/') }}"><i class="si si-home"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                            </li>
                            <li class="{{ request()->is('orders*') ? 'open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-basket-loaded"></i><span class="sidebar-mini-hide">{{ __('categories.orders') }}</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ route('orders.index') }}" class="{{ request()->is('orders') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.list', ['section' => __('categories.orders')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('orders.create') }}" class="{{ request()->is('orders/create') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.add_section', ['section' => __('categories.orders')]) }}</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('payment.index') }}" class="{{ request()->is('payment*') ? 'active' : '' }}"><i class="si si-wallet"></i><span class="sidebar-mini-hide">{{ __('categories.payment') }}</span></a>
                            </li>
                            <li class="{{ request()->is('meal*') ? 'open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-cup"></i><span class="sidebar-mini-hide">{{ __('categories.meal') }}</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ route('meal.index') }}" class="{{ request()->is('meal') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.list', ['section' => __('categories.meal')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('meal.create') }}" class="{{ request()->is('meal/create') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.add_section', ['section' => __('categories.meal')]) }}</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('day*') ? 'open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-book-open"></i><span class="sidebar-mini-hide">{{ __('categories.daily') }}</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ route('day.index') }}" class="{{ request()->is('day') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.list', ['section' => __('categories.daily')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('day.create') }}" class="{{ request()->is('day/create') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.add_section', ['section' => __('categories.daily')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ url('day/report') }}" class="{{ request()->is('day/report') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.report', ['section' => __('categories.daily')]) }}</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('organization*') ? 'open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-bank"></i><span class="sidebar-mini-hide">{{ __('categories.organs') }}</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ route('organization.index') }}" class="{{ request()->is('organization') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.list', ['section' => __('categories.organs')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('organization.create') }}" class="{{ request()->is('organization/create') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.add_section', ['section' => __('categories.organs')]) }}</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('users*') ? 'open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-user"></i><span class="sidebar-mini-hide">{{ __('categories.users') }}</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ route('users.index') }}" class="{{ request()->is('users') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.list', ['section' => __('categories.users')]) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('users.create') }}" class="{{ request()->is('users/create') ? 'text-white' : '' }}"><span class="sidebar-mini-hide">{{ __('dashboard.add_section', ['section' => __('categories.users')]) }}</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="content-header-section">
                    <!-- Toggle Sidebar -->
                    <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-navicon"></i>
                    </button>
                </div>

                <!-- Right Section -->
                <div class="content-header-section">
                    <div class="btn-group" role="group">
                        <a class="dropdown-item btn btn-rounded btn-dual-secondary" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('dashboard.log_out') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Header Loader -->
            <div id="page-header-loader" class="overlay-header bg-primary">
                <div class="content-header content-header-fullrow text-center">
                    <div class="content-header-item">
                        <i class="fa fa-sun-o fa-spin text-white"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Container -->
        <main id="main-container">
            <div class="content">
                @yield('content')
            </div>
        </main>

        <footer id="page-footer">
            <div class="content py-20 font-size-sm clearfix">
                <div class="float-right">
                    <a class="font-w600" href="https://abduazam.uz" target="_blank">Abduazam.uz</a> &copy; <span class="js-year-copy"></span>
                </div>
            </div>
        </footer>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('dashboard.close') }}</button>
                    <button type="button" class="btn btn-success" id="accept-info">{{ __('dashboard.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/assets/js/core/jquery.min.js"></script>
    <script src="/backend/assets/js/codebase.core.min.js"></script>
    <script src="/backend/assets/js/codebase.app.min.js"></script>
    <!-- Page JS Plugins -->
    <script src="/backend/assets/js/plugins/chartjs/Chart.min.js"></script>
    <!-- Page JS Code -->
    <script src="/backend/assets/js/pages/be_pages_dashboard.min.js"></script>
    <script src="/backend/assets/js/core/bootstrap.bundle.min.js"></script>
    <script src="/backend/assets/js/core/simplebar.min.js"></script>
    <script src="/backend/assets/js/core/jquery-scrollLock.min.js"></script>
    <script src="/backend/assets/js/core/jquery.appear.min.js"></script>
    <script src="/backend/assets/js/core/jquery.countTo.min.js"></script>
    <script src="/backend/assets/js/core/js.cookie.min.js"></script>
    <script src="/backend/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/backend/assets/js/plugins/select2/js/select2.full.min.js"></script>
    <script src="/backend/assets/js/plugins/simplemde/simplemde.min.js"></script>
    <script src="/backend/assets/js/plugins/flatpickr/flatpickr.min.js"></script>
    <script src="/backend/assets/js/script.js"></script>
    <script src="/backend/assets/js/map.js"></script>
    <script>
        jQuery(function () {
            Codebase.helpers(['select2', 'flatpickr', 'datepicker']);
        });

        let simplemde = new SimpleMDE({
            toolbar: ["bold", "italic", "link"],
            placeholder: "Text here..",
            tabSize: 2,
            spellChecker: false,
            status: false,
        });
    </script>
</body>
</html>
