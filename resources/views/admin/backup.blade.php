<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Title</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        Start creating your amazing application!
    </div>
    <div class="box-footer">
        Footer
    </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BK Bank - Admin</title>

    <link href="{{ url("lib/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/bootstrap/default/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/admin/css/AdminLTE.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/admin/css/skins/skin-yellow.min.css") }}" rel="stylesheet">

    <script src="{{ url("lib/sort.js") }}"></script>
</head>
<body class="sidebar-mini skin-yellow">
<div class="wrapper">
    <header class="main-header">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNav">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{ url("/") }}" class="logo">BK Bank</a>
        </div>
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url("/") }}">Home</a></li>
                    <li><a href="{{ url("/loans") }}">Loans</a></li>
                    <li>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Grants <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url("/grants/city") }}">Cities</a></li>
                            <li><a href="{{ url("/grants/entrance") }}">Entrance</a></li>
                            <li><a href="{{ url("/grants/activity") }}">Activity</a></li>
                            <li><a href="{{ url("/grants/id") }}">CIA</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url("/market") }}">Market</a></li>
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
                <li {{ ($page == 'loans') ? " class=active" : '' }}><a href="{{ url("/admin/loans") }}"><i class="fa fa-google-wallet"></i>Loans<span class='badge pull-right'>{{ \App\Loans::countPendReqs() ?? "" }}</span></a></li>
                <li {{ ($page == 'market') ? " class=active" : '' }}><a href="{{ url("/admin/market") }}"><i class="fa fa-usd"></i>Market</a></li>
                <li {{ ($page == 'so') ? " class=active" : '' }}><a href="{{ url("/admin/so") }}"><i class="fa fa-scribd"></i>Stratton Oakmont</a></li>
                <li {{ ($page == 'taxes') ? " class=active" : '' }}><a href="{{ url("/admin/taxes") }}"><i class="fa fa-percent"></i>Taxes</a></li>
                <li><a href="{{ url("/contact") }}"><i class="fa fa-phone"></i> Contact <span class='badge pull-right'>{{ \App\Contact::countPendReqs() ?? "" }}</span></a></li>
                <li class="header">Grants</li>
                <li {{ ($page == 'city') ? " class=active" : '' }}><a href="{{ url("/admin/city") }}"><i class="fa fa-wheelchair"></i>City Grants <span class='badge pull-right'>{{ \App\CityGrantRequests::countPendReqs() ?? "" }}</span></a></li>
                <li {{ ($page == 'entrance') ? " class=active" : '' }}><a href="{{ url("/admin/entrance") }}"><i class="fa fa-edge"></i>Entrance Aid <span class='badge pull-right'>{{ \App\EntranceAid::countPendReqs() ?? "" }}</span></a></li>
                <li {{ ($page == 'activity') ? " class=active" : '' }}><a href="{{ url("/admin/activity") }}"><i class="fa fa-adn"></i>Activity Grants <span class='badge pull-right'>{{ \App\ActivityGrant::countPendReqs() ?? "" }}</span></a></li>
                <li {{ ($page == 'id') ? " class=active" : '' }}><a href="{{ url("/admin/id") }}"><i class="fa fa-houzz"></i>ID Grants <span class='badge pull-right'>{{ \App\IDGrants::countPendReqs() ?? "" }}</span></a></li>

                <li class="header">System</li>
                <li {{ ($page == 'settings') ? " class=active" : '' }}><a href="{{ url("/admin/settings") }}"><i class="fa fa-cogs"></i> Settings</a></li>
                <li {{ ($page == 'logs') ? " class=active" : '' }}><a href="{{ url("/admin/logs") }}"><i class="fa fa-list-ul"></i> Logs</a></li>
            </ul>
        </div>
    </aside>
    <div class="content-wrapper">
        @if ($errors->any() > 0)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4 style="font-size: 20px;">Error!</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (isset($success) && count($success))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4 style="font-size: 20px;">Success!</h4>
                <ul>
                    @foreach ($success as $suc)
                        <li>{!! $suc !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield("content")
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <p>Version 3.0.0</p>
        </div>
    </footer>
</div>
<script src="{{ url("lib/chart/Chart.min.js") }}"></script>
<script src="{{ url("lib/jquery/jquery.min.js") }}"></script>
<script src="{{ url("lib/bootstrap/default/js/bootstrap.min.js") }}"></script>

<script>
    $('a.editGrant').click(function(ev) {
        ev.preventDefault();
        var gID = $(this).data('id');
        var json;
        $.get("{{ url("api/gInfo") }}/" + gID, function(html) {
            json = jQuery.parseJSON(html);
            replaceShit(json);
        });

        function replaceShit(json) {
            console.log(json.amount);
            document.getElementById("gNumber").value = json.grantNum;
            document.getElementById("gAmount").value = json.amount;
            document.getElementById("gInf").value = json.infPerCity;
            if (json.irondome == 1)
                document.getElementById("gIron").checked = true;
            else
                document.getElementById("gIron").checked = false; // Check if it's false so if they go to another grant it is unchecked
            if (json.NRF == 1)
                document.getElementById("gNRF").checked = true;
            else
                document.getElementById("gNRF").checked = false;

            document.getElementById("gMMR").value = json.mmrScore;
            json.notes = json.notes.replace(/&lt;/g, "<"); // The < comes in weird so just replace it
            document.getElementById("gNotes").value = json.notes;
            document.getElementById("editGID").value = json.id;
            document.getElementById("delGID").value = json.id;
        }
    });
</script>
</body>
</html>