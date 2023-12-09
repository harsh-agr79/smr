<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="theme-color" content="#ffb300" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="apple-touch-icon" href="{{ asset('app1.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <title>Admin</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('/assets/light.png') }}">
    <link rel="stylesheet" href="{{ asset('/assets/style.css') }}">
    <link rel="icon" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('icons/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('icons/safari-pinned-tab.svg') }}" color="#ffb300">
    <link href="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/admin-materialize.min.css?v=8850535670742419153"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Exo' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body class="has-fixed-sidenav">
    <header>
        <div class="navbar-fixed">
            <nav class="navbar white">
                <div class="nav-wrapper">
                    <a href="{{ url('/') }}" class="brand-logo grey-text text-darken-4"
                        style="padding-top: 10px;"><img src="{{ asset('logo/logo2.png') }}" style="height: 40px;"
                            alt=""></a>
                    <ul id="nav-mobile" class="right">
                        <li class="hide-on-med-and-down">{{ getNepaliDate(date('Y-m-d')) }}</li>
                        <li class="hide-on-med-and-down"><a href="#!" data-target="dropdown1"
                                class="dropdown-trigger "><i class="material-icons">notifications</i></a>
                            <div id="dropdown1" class="dropdown-content notifications" tabindex="0">
                                <div class="notifications-title" tabindex="0">notifications</div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">Joe Smith made a
                                            purchase</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">Daily Traffic Update</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">New User Joined</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a href="#!" data-target="settings-dropdown" class="dropdown-trigger "><i
                                    class="material-icons">settings</i></a>

                            <ul id='settings-dropdown' class='dropdown-content center'>
                                <li class="center"><a class="center" href="{{ url('/admin/profile') }}">Profile</a>
                                </li>
                                <li class="center"><a class="center" href="{{ url('/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul><a href="#!" data-target="sidenav-left" class="sidenav-trigger left"><i
                            class="material-icons black-text">menu</i></a>
                </div>
            </nav>
        </div>
        <ul id="sidenav-left" class="sidenav sidenav-fixed white" style="transform: translateX(0%);">
            <li><a href="{{ url('/') }}" class="logo-container">{{ $admin->name }}<i
                        class="material-icons left">spa</i></a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li><a href="{{ url('dashboard') }}" class=" active">Dashboard<i
                                class="material-icons">web</i></a></li>
                    <li class="bold  green"><a class="collapsible-header" tabindex="0">Company<i
                                class="material-icons chevron">chevron_left</i></a>
                        <div class="collapsible-body" style="">
                            <ul>
                                @if (session()->get('ADMIN_TYPE') == 'superuser')
                                    <li><a href="{{ url('admins') }}" class=" active">Admins<i
                                                class="material-symbols-outlined">
                                                shield_person
                                            </i></a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li class="bold "><a class="collapsible-header" tabindex="0">Customers<i
                                class="material-icons chevron">chevron_left</i></a>
                        <div class="collapsible-body" style="">
                            <ul>
                                <li><a href="{{ url('customers') }}" class=" active">List<i
                                            class="material-symbols-outlined">group</i></a></li>
                                <li><a href="{{ url('customers/add') }}" class=" active">Add Customers<i
                                            class="material-symbols-outlined">person_add</i></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="bold "><a class="collapsible-header" tabindex="0">Inventory<i
                                class="material-icons chevron">chevron_left</i></a>
                        <div class="collapsible-body" style="">
                            <ul>
                                <li><a href="{{ url('brands') }}" class=" active">Brands<i
                                            class="material-symbols-outlined">store</i></a></li>
                                <li><a href="{{ url('category') }}" class=" active">Categories<i
                                            class="material-symbols-outlined">category</i></a></li>
                                            <li><a href="{{ url('products') }}" class=" active">Products<i
                                                class="material-symbols-outlined">inventory</i></a></li>
                                                <li><a href="{{ url('products/add') }}" class=" active">Add Product<i
                                                    class="material-symbols-outlined">add_circle</i></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>



    </header>

    <main>
        @yield('main')
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('/assets/script.js') }}"></script>
    <script src="{{ asset('/assets/select.js') }}"></script>
    {{-- <script src="{{ asset('/assets/sorttable.js') }}"></script> --}}
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
</body>

</html>
