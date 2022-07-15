<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IMS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/argon.css?v=1.1.0')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('css/slimselect.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('css/style.css')}}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <input type="hidden" value="{{app()->getLocale()}}" id="locale">
        @auth
        <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white"
            id="sidenav-main">
            <div class="scroll-wrapper scrollbar-inner" style="position: relative;">
                <div class="scrollbar-inner scroll-content scroll-scrolly_visible"
                    style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 95vh;">
                    <!-- Brand -->
                    <div class="sidenav-header  d-flex  align-items-center">
                        <a class="navbar-brand" href="/">
                            <i class="fas fa-warehouse text-indigo"></i>
                        </a>
                        <div class=" ml-auto ">
                            <!-- Sidenav toggler -->
                            <div class="sidenav-toggler d-none d-xl-block active" data-action="sidenav-unpin"
                                data-target="#sidenav-main">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navbar-inner">
                        <!-- Collapse -->
                        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                            <!-- Nav items -->
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('/') ? 'active' : ''}}" href="/">
                                        <i class="fas fa-store text-primary"></i>
                                        <span class="nav-link-text">{{__('Dashboard')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('products*') ? 'active' : ''}}"
                                        href="#navbar-examples" data-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="navbar-examples">
                                        <i class="ni ni-single-copy-04 text-orange"></i>
                                        <span class="nav-link-text">{{__('Products')}}</span>
                                    </a>
                                    <div class="collapse" id="navbar-examples">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route('products.create')}}"
                                                    class="nav-link {{Request::is('products/create') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Create Product')}} </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{Request::is('products') ? 'active' : ''}}">
                                                <a href="{{route('products.index')}}" class="nav-link">
                                                    <span class="sidenav-normal"> {{__('Product List')}} </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('sales*') ? 'active' : ''}}"
                                        href="#navbar-components" data-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="navbar-components">
                                        <i class="fas fa-cash-register text-info" aria-hidden="true"></i>
                                        <span class="nav-link-text">{{__('Sales')}}</span>
                                    </a>
                                    <div class="collapse" id="navbar-components">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route('sales.create')}}"
                                                    class="nav-link {{Request::is('sales/create') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Register Sale')}} </span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{route('sales.index')}}"
                                                    class="nav-link {{Request::is('sales') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Sale List')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('purchases*') ? 'active' : ''}}"
                                        href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="navbar-forms">
                                        <i class="ni ni-basket text-pink"></i>
                                        <span class="nav-link-text">{{__('Purchases')}}</span>
                                    </a>
                                    <div class="collapse" id="navbar-forms">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route('purchases.create')}}"
                                                    class="nav-link {{Request::is('purchases/create') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Register Purchase')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{route('purchases.index')}}"
                                                    class="nav-link {{Request::is('purchases') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Purchase List')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('clients*') ? 'active' : ''}}"
                                        href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="navbar-tables">
                                        <i class="fas fa-handshake text-success" aria-hidden="true"></i>
                                        <span class="nav-link-text">{{__('Clients')}}</span>
                                    </a>
                                    <div class="collapse" id="navbar-tables">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route('clients.create')}}"
                                                    class="nav-link {{Request::is('clients/create') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Create Client')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{route('clients.index')}}"
                                                    class="nav-link {{Request::is('clients') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Client List')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('providers*') ? 'active' : ''}}"
                                        href="#navbar-maps" data-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="navbar-maps">
                                        <i class="fas fa-truck text-default"></i>
                                        <span class="nav-link-text">{{__('Suppliers')}}</span>
                                    </a>
                                    <div class="collapse" id="navbar-maps">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route('providers.create')}}"
                                                    class="nav-link {{Request::is('providers') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Create Supplier')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{route('providers.index')}}"
                                                    class="nav-link {{Request::is('providers') ? 'active' : ''}}">
                                                    <span class="sidenav-normal"> {{__('Supplier List')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{Request::is('transactions*') ? 'active' : ''}}"
                                        href="{{route('transactions.index')}}">
                                        <i class="fas fa-piggy-bank text-danger"></i>
                                        <span class="nav-link-text">{{__('Transactions')}}</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Divider -->
                            <hr class="my-3">
                            <!-- Heading -->
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal">Options</span>
                            </h6>
                            <!-- Navigation -->
                            <ul class="navbar-nav mb-md-3">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('categories.index')}}">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="nav-link-text">{{__('Categories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('subcategories.index')}}">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="nav-link-text">{{__('Subcategories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('brands.index')}}">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="nav-link-text">{{__('Brands')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('specifications.index')}}">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="nav-link-text">{{__('Product Specifications')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('methods.index')}}">
                                        <i class="fas fa-money-bill    "></i>
                                        <span class="nav-link-text">{{__('Payment Methods')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="scroll-element scroll-x scroll-scrolly_visible">
                    <div class="scroll-element_outer">
                        <div class="scroll-element_size"></div>
                        <div class="scroll-element_track"></div>
                        <div class="scroll-bar" style="width: 0px; left: 0px;"></div>
                    </div>
                </div>
                <div class="scroll-element scroll-y scroll-scrolly_visible">
                    <div class="scroll-element_outer">
                        <div class="scroll-element_size"></div>
                        <div class="scroll-element_track"></div>
                        <div class="scroll-bar" style="height: 62px; top: 0px;"></div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="main-content" id="panel">
            <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Search form -->
                        @php ($names = array('Search'=>__('Search'), 'Products'=>__('Products'),
                        'Categories'=>__('Categories'), 'Clients'=>__('Clients'), 'Suppliers'=>__('Suppliers'),
                        'Unit'=>__('Unit')))

                        <search-component :text={!! json_encode($names) !!}></search-component>
                        <!-- Navbar links -->
                        <ul class="navbar-nav align-items-center  ml-md-auto ">
                            <li class="nav-item d-xl-none">
                                <!-- Sidenav toggler -->
                                <div class="pr-3 sidenav-toggler sidenav-toggler-dark active" data-action="sidenav-pin"
                                    data-target="#sidenav-main">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item d-sm-none">
                                <a class="nav-link" href="#" data-action="search-show"
                                    data-target="#navbar-search-main">
                                    <i class="ni ni-zoom-split-in"></i>
                                </a>
                            </li>
                            @if(count(config('app.languages')) > 1)
                            <li class="nav-item dropdown d-md-down-none">
                                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @foreach(config('app.languages') as $langLocale => $langName)
                                    <a class="dropdown-item fw-bold"
                                        href="{{ url()->current() }}?change_language={{ $langLocale }}">{{
                                        strtoupper($langLocale) }}
                                        ({{ $langName }})</a>
                                    @endforeach
                                </div>
                            </li>
                            @endif
                            @php ($text = array('Stock'=>__('On Stock'), 'Have'=>__('You have'),
                            'notifications'=>__('notifications')))
                            <notifications-component :text="{{ json_encode($text) }}"></notifications-component>

                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="ni ni-ungroup"></i>
                                </a>
                                <div
                                    class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
                                    <div class="row shortcuts px-4">
                                        <a href="/transactions/income" class="col-4 shortcut-item">
                                            <span class="shortcut-media avatar rounded-circle bg-gradient-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M0 0h1v15h15v1H0V0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5z" />
                                                </svg>
                                            </span>
                                            <small>{{__('Income')}}</small>
                                        </a>
                                        <a href="/transactions/expense" class="col-4 shortcut-item">
                                            <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-graph-down" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M0 0h1v15h15v1H0V0zm10 11.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-1 0v2.6l-3.613-4.417a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61L13.445 11H10.5a.5.5 0 0 0-.5.5z" />
                                                </svg>
                                            </span>
                                            <small>{{__('Expense')}}</small>
                                        </a>
                                        <a href="/transactions/payment" class="col-4 shortcut-item">
                                            <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                                                <i class="ni ni-credit-card"></i>
                                            </span>
                                            <small>{{__('Payments')}}</small>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                            <li class="nav-item dropdown">
                                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">
                                        <span class="avatar avatar-sm rounded-circle">
                                            <i class="fas fa-user-alt    "></i>
                                        </span>
                                        <div class="media-body  ml-2  d-none d-lg-block">
                                            <span class="mb-0 text-sm  font-weight-bold">{{auth()->user()->name}}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu  dropdown-menu-right ">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <span>{{__('Logout')}}</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="header bg-primary">
                <div class="container-fluid pb-6">
                    <div class="header-body pb-4">
                        <div class="row align-items-center py-2">
                            <div class="col-lg-6 col-7">
                                <nav aria-label="breadcrumb" class="d-md-inline-block ml-md-4">
                                    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                        <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="/">{{__('Dashboard')}}</a></li>
                                        @for($i = 2; $i <= count(Request::segments())-1; $i++) <li
                                            class="breadcrumb-item">
                                            <a
                                                href="{{ URL::to( implode( '/', array_slice(Request::segments(), 0 ,$i, true)))}}">
                                                @if(!is_numeric(Request::segment($i)) )
                                                @if(Request::segment($i) != 'providers')
                                                {{__(ucfirst(trans(Request::segment($i))))}}
                                                @else
                                                {{__('Suppliers')}}
                                                @endif
                                                @endif
                                            </a>
                                            </li>
                                            @endfor
                                    </ol>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endauth
            <div class="container-fluid" style="min-height: 70vh;">
                @yield('content')
            </div>
            <footer class="footer mx-4 mt-1 bg-white rounded-top">
                <div class="row align-items-center justify-content-lg-between px-4">
                    <div class="col-lg-6">
                        <div class="copyright text-center  text-lg-left  text-muted">
                            © 2021 {{__('All rights reserved.')}}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="nav nav-footer justify-content-center justify-content-lg-end">
                            <span class="text-muted copyright"></span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('assets/vendor/js-cookie/js.cookie.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js')}}" defer></script>
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js')}}" defer></script>
    <script src="{{asset('assets/js/argon.js?v=1.1.0')}}" defer></script>
    <script src="{{asset('js/slimselect.js')}}" defer></script>
    <script src="{{asset('js/custom.js')}}" defer></script>
    @stack('js')
</body>

</html>
