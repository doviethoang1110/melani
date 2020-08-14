<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{url('/public/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{url('/public/admin')}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/public/admin')}}/css/AdminLTE.css">
    <link rel="stylesheet" href="{{url('/public/admin')}}/css/_all-skins.min.css">
    <link rel="stylesheet" href="{{url('/public/admin')}}/css/style.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('/public/admin') }}/css/select2.min.css">
    <link rel="stylesheet" href="{{ url('/public/admin') }}/css/bootstrap-duallistbox.css">

    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
    @yield('css')
    <script>
        var base_url = function () {
            return "{{ url('') }}";
        }

        var akey = function () {
            return 'dojkDpbuTKtuZ8vAZvP8JQ2OCOVtQXxPv1dWA0I';
        }
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="../../index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <ul class="nav navbar-nav navbar-right" style="margin-right: 10px">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi {{ Auth::user()->name }}<b
                            class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Thông tin</a></li>
                        <li><a href="{{ route('logout') }}" onclick="return confirm('Bạn có chắc không ?')">Thoát tài
                                khoản</a></li>
                    </ul>
                </li>
            </ul>

        </nav>
    </header>
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto;">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php
            $name = Auth::user()->getRoleNames();
            $id = \Spatie\Permission\Models\Role::whereIn('name', $name)->pluck('id');
            $rolePermission = \Spatie\Permission\Models\Permission::join('role_has_permissions', 'role_has_permissions.permission_id', 'permissions.id')
                ->whereIn('role_has_permissions.role_id', $id)->pluck('name');
            ?>
            <?php $menu = config('menu');?>
            <ul class="sidebar-menu tree" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                @foreach ($menu as $m)
                    <?php $class = !empty($m['item']) ? 'treeview' : '';?>
                    @if($class=='treeview')
                        @if(!empty($m['can']))
                            @foreach($m['can'] as $v)
                                @if($rolePermission->contains($v))
                                    <li class="{{ $class }}">
                                        <a href="{{ route($m['route']) }}" id="check">
                                            <i class="{{ $m['icon'] }}"></i>
                                            <span>{{ $m['name'] }}</span>
                                            <span class="pull-right-container">
                                  <span><i class="glyphicon glyphicon-chevron-left" id="icon"></i></span>
                                </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            @foreach($m['item'] as $n)
                                                @if(!empty($n['can']))
                                                    @foreach($n['can'] as $t)
                                                        @if($rolePermission->contains($t))
                                                            <li><a href="{{ route($n['route']) }}"><i class="glyphicon glyphicon-briefcase"></i>{{$n['name']}}</a></li>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    @break
                                @endif
                            @endforeach
                        @endif
                    @else
                        @if(!empty($m['can']))
                            @foreach($m['can'] as $v)
                                @if($rolePermission->contains($v))
                                    <li class="{{ $class }}">
                                        <a href="{{ route($m['route']) }}">
                                            <i class="{{ $m['icon'] }}"></i> <span>{{ $m['name'] }}</span>
                                        </a>
                                    </li>
                                    @break
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- =============================================== -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title')
            </h1>
            <!-- <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li><a href="#">Examples</a></li>
              <li class="active">Blank page</li>
            </ol> -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">


                </div>
                <div class="box-body">
                    @yield('main')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->

<script src="{{ url('/public/admin') }}/js/jquery.min.js"></script>
<script src="{{url('/public/admin')}}/js/bootstrap.min.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{url('/public/admin')}}/js/adminlte.min.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{url('public/admin')}}/js/Chart.min.js"></script>
<script src="{{url('public/admin')}}/js/utils.js"></script>
<script src="{{ url('public/admin') }}/js/select2.min.js"></script>
<script src="{{ url('/public/admin') }}/js/jquery.bootstrap-duallistbox.js"></script>
<script !src="">
    var check = true;
    $('#check').click(function () {
        if (check == true) {
            $('#icon').attr('class', 'glyphicon glyphicon-chevron-down');
            check = false;
        } else if (check == false) {
            $('#icon').attr('class', 'glyphicon glyphicon-chevron-left');
            check = true;
        }
    });
</script>
@yield('js')
</body>
</html>
