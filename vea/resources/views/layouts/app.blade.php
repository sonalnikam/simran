<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Vikram's English Academy : VEA">
    <meta name="author" content="Simran Kahlon">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="{{ asset('img/vealogo.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Vikram's English Academy : VEA</title>

    <!-- Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>


    </script>
    <!-- <style type="text/css">
    .enquiry {
        display: block;
    overflow: scroll;
    white-space: nowrap;
    }
    </style> -->


    

</head>

<!-- BODY options, add following classes to body to change options
        1. 'compact-nav'          - Switch sidebar to minified version (width 50px)
        2. 'sidebar-nav'          - Navigation on the left
            2.1. 'sidebar-off-canvas'   - Off-Canvas
                2.1.1 'sidebar-off-canvas-push' - Off-Canvas which move content
                2.1.2 'sidebar-off-canvas-with-shadow'  - Add shadow to body elements
        3. 'fixed-nav'            - Fixed navigation
        4. 'navbar-fixed'         - Fixed navbar
        5. 'footer-fixed'         - Fixed navbar
    -->

<body class="navbar-fixed sidebar-nav fixed-nav">
    <header class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">☰</button>
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav hidden-md-down">
                <li class="nav-item">
                    <a class="nav-link navbar-toggler layout-toggler" href="#">☰</a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" href="{{ url('/') }}">Vikram's English Academy : VEA</a>
                </li>
                
            </ul>
            <ul class="nav navbar-nav float-xs-right">
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('img/user-icon.png') }}" class="img-avatar" alt="{{ Auth::user()->name }}">
                        <span class="hidden-md-down">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-xs-center">
                            <strong>Settings</strong>
                        </div>
                        <a class="dropdown-item" href="{{url('/home/changepassword')}}"><i class="fa fa-user"></i> Change Password</a>
                        
                        <!--<a class="dropdown-item" href="{{ url('/logout') }}"><i class="fa fa-lock"></i> Logout</a>-->
                        <a class="dropdown-item" href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fa fa-lock"></i>Logout</a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!--Left Nav Bar-->
    @include('commons.leftnav')
    <!--/Left Nav Bar-->

    <!-- Main content -->
    <main class="main">
        <!-- Breadcrumb -->
        
        @yield('breadcrumb')

        <div class="container-fluid">
            <div class="animated fadeIn">
                <!--MAIN CONTENT-->
                @yield('content')
                <!--/MAIN CONTENT-->
            </div>

        </div>
        <!-- /.conainer-fluid -->
    </main>
    <!--Footer-->
    @include('commons.footer')
    <!--/Footer-->

    <!-- Bootstrap and necessary plugins -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/tether/dist/js/tether.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/pace/pace.min.js') }}"></script>


    <!-- Plugins and scripts required by all views -->
    <script src="{{ asset('bower_components/chart.js/dist/Chart.min.js') }}"></script>

    
    
    <!-- GenesisUI main scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    @if($flash = session('message'))
    <script>
    toastr.options = {
                
                positionClass: 'toast-bottom-right',
               
            };
    toastr.info('{{$flash}}');
    </script>
    @endif
    @yield('modalfun')
    @yield('javascriptfunctions')
    
</body>
</html>     