<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ App::environment('local') ? "(Dev)" : "" }} Black Bank - Admin</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="{{ url("lib/bootstrap/default/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/admin/css/AdminLTE.min.css") }}" rel="stylesheet">
    <script src="{{ url("lib/jquery/jquery.min.js") }}"></script>
    <link href="{{ url("lib/admin/css/skins/skin-yellow.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/icheck/skins/square/orange.css") }}" rel="stylesheet">
    <script src="{{ url("lib/icheck/icheck.min.js") }}"></script>
    <script src="{{ url("/lib/sort.js") }}"></script>
    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-orange',
                radioClass: 'iradio_square-orange',
                increaseArea: '20%' // optional
            });
        });
    </script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .info-box-icon i {
            line-height: 90px;
        }
    </style>
</head>
<body class="sidebar-mini skin-yellow">
<div class="wrapper">
    <header class="main-header">
        <a href="{{ url("/") }}" class="logo navbar-header">
            <span class="logo-mini">CamNet</span>
            <span class="logo-lg"><b>Camelot</b></span>
        </a>
        <nav class="navbar navbar-static-top container-fluid" role="navigation">
            <button type="button" class="navbar-toggle sidebar-toggle pull-right visible-xs" data-toggle="collapse" data-target="#mainNav" style="display: inline-block">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#" class="sidebar-toggle visible-xs" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url("/") }}">Home</a></li>
                    <li><a href="{{ url("ia") }}">Apply</a></li>
                    <li><a href="{{ url("/loans") }}">Loans</a></li>
                    <li><a href="{{ url("/market") }}">Market</a></li>
                    <li><a class="dropdown-toggle" data-toggle="dropdown" href="#">Grants<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url("/grants/city") }}">Cities</a></li>
                            <li><a href="{{ url("/grants/entrance") }}">Entrance</a></li>
                            <li><a href="{{ url("/grants/activity") }}">Activity</a></li>
                            <li><a href="{{ url("/grants/oil") }}">Oil</a></li>
                            <li><a href="{{ url("/grants/nuke") }}">Nukes</a></li>
                            <li><a href="{{ url("/grants/egr") }}">EGR</a></li>
                        </ul>
                    </li>
                    <li><a class="dropdown-toggle" data-toggle="dropdown" href="#">Project Grants<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url("/grants/id") }}">CIA</a></li>
                            <li><a href="{{ url("/grants/irondome") }}">Iron Dome</a></li>
                            <li><a href="{{ url("/grants/mlp") }}">MLP</a></li>
                            <li><a href="{{ url("/grants/pb") }}">PB</a></li>
                            <li><a href="{{ url("/grants/cce") }}">CCE</a></li>
                            <li><a href="{{ url("/grants/nrf") }}">NRF</a></li>
                            <li><a href="{{ url("/grants/egr") }}">EGR</a></li>
                        </ul>
                    </li>
                    <li><a class="dropdown-toggle" data-toggle="dropdown" href="#">Defense <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url("signin") }}">Sign In</a></li>
                        </ul>
                    </li>
                    @if (Auth::guest())
                        <li><a href="{{ url("/login") }}">Login</a></li>
                    @else
                        <li><a href="{{ url("/logout") }}">Logout</a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    @if (Gate::denies("admin"))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 style="font-size: 20px;">Error!</h4>
            <ul>
                <li>You are not authorized to view this page</li>
                {{ die() }}
            </ul>
        </div>
    @endif
    <aside class="main-sidebar">
        <div class="sidebar">
            <div class="user-panel">
                <div class="info" style="position:static;">
                    <p>{{ Auth::user()->username }}</p>
                    <p class="text-sm"><i class="fa fa-circle text-success"></i> {{ Auth::user()->title }}</p>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">Main Navigation</li>
                <li {{ ($page == 'dashboard') ? " class=active" : '' }}><a href="{{ url("/admin") }}"><i class="fa fa-home"></i>Dashboard</a></li>
                <li {{ ($page == 'users') ? " class=active" : '' }}><a href="{{ url("/admin/users") }}"><i class="fa fa-user"></i>Users</a></li>
                <li {{ ($page == 'members') ? " class=active" : '' }}><a href="{{ url("/admin/members") }}"><i class="fa fa-users"></i>Members</a></li>
                <li><a href="{{ url("/contact") }}"><i class="fa fa-phone"></i> Contact <span class='badge bg-yellow pull-right'>{{ \App\Models\Contact::countPendReqs() ?? "" }}</span></a></li>
                <li class="header">Bank</li>
                <li {{ ($page == 'loans') ? " class=active" : '' }}><a href="{{ url("/admin/loans") }}"><i class="fa fa-google-wallet"></i>Loans<span class='badge bg-yellow pull-right'>{{ \App\Models\Loans::countPendReqs() ?? "" }}</span></a></li>
                <li {{ ($page == 'accounts') ? " class=active" : '' }}><a href="{{ url("/admin/accounts") }}"><i class="fa fa-gg"></i>Accounts</a></li>
                <li class="treeview @if (in_array($page, ["city", "entrance", "activity", "id", "oil", "egr", "nukes"])) active @endif">
                    <a href="#">
                        <i class="fa fa-gratipay" aria-hidden="true"></i>
                        <span>Grants</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="@if (!in_array($page, ["city", "entrance", "activity", "oil", "nukes"])) display: none; @endif">
                        <li {{ ($page == 'city') ? " class=active" : '' }}><a href="{{ url("/admin/city") }}"><i class="fa fa-circle-o"></i>City Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\CityGrantRequests::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'entrance') ? " class=active" : '' }}><a href="{{ url("/admin/entrance") }}"><i class="fa fa-circle-o"></i>Entrance Aid <span class='badge bg-yellow'>{{ \App\Models\Grants\EntranceAid::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'activity') ? " class=active" : '' }}><a href="{{ url("/admin/activity") }}"><i class="fa fa-circle-o"></i>Activity Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\ActivityGrant::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'oil') ? " class=active" : '' }}><a href="{{ url("/admin/oil") }}"><i class="fa fa-circle-o"></i>Oil Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\OilGrant::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'nukes') ? " class=active" : '' }}><a href="{{ url("/admin/nukes") }}"><i class="fa fa-circle-o"></i>Nuke Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\NukeGrants::countPendReqs() ?? "" }}</span></a></li>
                    </ul>
                </li>
                <li class="treeview @if (in_array($page, ["irondomeGrants", "id", "pb", "cce", "nrf", "egr", "mlp"])) active @endif">
                    <a href="#">
                        <i class="fa fa-gratipay" aria-hidden="true"></i>
                        <span>GorgeNet</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="@if (!in_array($page, ["irondomeGrants", "id", "pb", "cce", "nrf", "egr", "mlp"])) display: none; @endif">
                        <li {{ ($page == 'irondome') ? " class=active" : '' }}><a href="{{ url("/admin/irondome") }}"><i class="fa fa-circle-o"></i>ID Grants<span class='badge bg-yellow'>{{ \App\Models\Grants\irondomeGrants::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'id') ? " class=active" : '' }}><a href="{{ url("/admin/id") }}"><i class="fa fa-circle-o"></i>CIA Grants<span class='badge bg-yellow'>{{ \App\Models\Grants\EntranceAid::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'pb') ? " class=active" : '' }}><a href="{{ url("/admin/pb") }}"><i class="fa fa-circle-o"></i>PB Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\pbGrants::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'nrf') ? " class=active" : '' }}><a href="{{ url("/admin/nrf") }}"><i class="fa fa-circle-o"></i>NRF Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\nrfGrants::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'egr') ? " class=active" : '' }}><a href="{{ url("/admin/egr") }}"><i class="fa fa-circle-o"></i>EGR Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\EGRGrant::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'mlp') ? " class=active" : '' }}><a href="{{ url("/admin/mlp") }}"><i class="fa fa-circle-o"></i>MLP Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\mlpGrants::countPendReqs() ?? "" }}</span></a></li>
                        <li {{ ($page == 'cce') ? " class=active" : '' }}><a href="{{ url("/admin/cce") }}"><i class="fa fa-circle-o"></i>CCE Grants <span class='badge bg-yellow'>{{ \App\Models\Grants\cceGrants::countPendReqs() ?? "" }}</span></a></li>
                    </ul>
                </li>
                <li {{ ($page == 'market') ? " class=active" : '' }}><a href="{{ url("/admin/market") }}"><i class="fa fa-usd"></i>Market</a></li>
                <li {{ ($page == 'so') ? " class=active" : '' }}><a href="{{ url("/admin/so") }}"><i class="fa fa-scribd"></i>Stratton Oakmont</a></li>
                <li {{ ($page == 'taxes') ? " class=active" : '' }}><a href="{{ url("/admin/taxes") }}"><i class="fa fa-percent"></i>Taxes</a></li>
                <li {{ ($page == 'budget') ? " class=active" : '' }}><a href="{{ url("/budget") }}"><i class="fa fa-file-excel-o"></i>Budget Spreadsheet</a></li>

                <li class="header">Internal Affairs</li>
                <li class="treeview @if(in_array($page, ["applicants", "academy", "track", "unmask"])) active @endif">
                    <a href="#">
                        <i class="fa fa-compass" aria-hidden="true"></i>
                        <span>Tibernet</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="@if (!in_array($page, ["applicants", "academy", "track", "unmask"])) display: none; @endif">
                        <li {{ ($page == 'applicants') ? " class=active" : '' }}><a href="{{ url("/ia/applicants") }}"><i class="fa fa-home"></i>Applicants</a></li>
                        <li {{ ($page == 'academy') ? " class=active" : '' }}><a href="{{ url("/ia/academy") }}"><i class="fa fa-user"></i>Academy</a></li>
                        <li {{ ($page == 'track') ? " class=active" : '' }}><a href="{{ url("/ia/track") }}"><i class="fa fa-users"></i>Tracking</a></li>
                        <li {{ ($page == 'unmask') ? " class=active" : '' }}><a href="{{ url("/ia/unmask") }}"><i class="fa fa-users"></i>Unmask</a></li>
                    </ul>
                <li {{ ($page == 'recruiting') ? " class=active" : '' }}><a href="{{ url("/ia/recruiting") }}"><i class="fa fa-sign-in"></i>Recruiting</a></li>

                <li class="header">Defense</li>
                <li {{ ($page == 'targets') ? " class=active" : '' }}><a href="{{ url("/defense/targets") }}"><i class="fa fa-bullseye"></i>Targets</a></li>
                <li {{ ($page == 'spies') ? " class=active" : '' }}><a href="{{ url("/defense/spies") }}"><i class="fa fa-bullseye"></i>Spies</a></li>
                <li {{ ($page == 'mmr') ? " class=active" : '' }}><a href="{{ url("/defense/mmr") }}"><i class="fa fa-power-off"></i>MMR</a></li>

                <li class="header">System</li>
                <li {{ ($page == 'settings') ? " class=active" : '' }}><a href="{{ url("/admin/settings") }}"><i class="fa fa-cogs"></i> Settings</a></li>
                <li {{ ($page == 'logs') ? " class=active" : '' }}><a href="{{ url("/admin/logs") }}"><i class="fa fa-list-ul"></i> Logs</a></li>
            </ul>
        </div>
    </aside>

    <div class="content-wrapper">
        @yield("content")
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <p>{{ env("APP_VERSION") }}</p>
        </div>
        <p><a href="https://bitbucket.org/yosodog/bank/issues" target="_blank">Issue Tracker</a></p>
    </footer>
</div>
<script src="{{ url("lib/chart/Chart.min.js") }}"></script>
<script src="{{ url("lib/bootstrap/default/js/bootstrap.min.js") }}"></script>
<script src="{{ url("/lib/admin/js/app.min.js") }}"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });
</script>
@yield("scripts")
</body>
</html>