<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ App::environment('local') ? "(Dev)" : "" }} CamNet</title>

    <link rel="stylesheet" href="{{ url("/lib/bootstrap/paper/paper.min.css") }}">
    <link rel="stylesheet" href="{{ url("/css/default.css") }}">

    @yield("headScripts")
    </head>
    <body>
    <div class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a href="{{ url("/") }}" class="navbar-brand">CamNet {{ App::environment('local') ? "(Dev)" : "" }}</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url("/ia/") }}">Apply</a></li>
                    <li><a href="{{ url("/accounts") }}">Accounts</a></li>                      
                    <li><a href="{{ url("/loans") }}">Loans</a></li>
                    <li><a href="{{ url("/market") }}">Market</a></li>
                    <li><a class="dropdown-toggle" data-toggle="dropdown" href="#">Grants<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url("/grants/city") }}">Cities</a></li>
                            <li><a href="{{ url("/grants/entrance") }}">Entrance</a></li>
                            <li><a href="{{ url("/grants/activity") }}">Activity</a></li>
                            <li><a href="{{ url("/grants/oil") }}">Oil</a></li>
                            <li><a href="{{ url("/grants/nuke") }}">Nukes</a></li>
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
                            @if (Auth::check())
                                <li><a href="{{ url("/defense/dashboard") }}">Dashboard</a></li>
                            @endif
                            <li><a href="{{ url("/signin") }}">Sign In</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        @if (Auth::user()->isAdmin)
                            <li><a href="{{ url("/admin") }}">Admin <span class="badge">{{ \App\Classes\Output::countPendingReqs() ?? "" }}</span></a></li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="text-capitalize">{{ Auth::user()->username }}</span> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">                           
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
        <main class="container">
            @if (isset($errors) && count($errors) > 0)
                {{ App\Classes\Output::genAlert($errors->all(), "danger", "Error") }}
            @endif
            @if (isset($output->errors) && count($output->errors))
                {{ App\Classes\Output::genAlert($output->errors, "danger", "Error") }}
            @endif
            @if (isset($output->warning) && count($output->warning))
                {{ App\Classes\Output::genAlert($output->warning, "warning", "Warning") }}
            @endif
            @if (isset($output->successes) && count($output->successes))
                {{ App\Classes\Output::genAlert($output->successes, "success", "Success") }}
            @endif
            @if (isset($output->info) && count($output->info))
                {{ App\Classes\Output::genAlert($output->info) }}
            @endif

            @yield("content")
        </main>
        <footer>
            <nav class="text-center">
                <a href="{{ url("/contact") }}">Contact</a>
                @if (!Auth::guest() && Auth::user()->isAdmin)
                    <span class='badge'>{{ \App\Models\Contact::countPendReqs() ?? "" }}</span>
                @endif
                     |
                <a href="https://forum.politicsandwar.com/" target="_blank">Forums</a> |
                <a href="https://blazeti.me/ayylmao2/" target="_blank">ayy lmao</a>
            </nav>

            <script src="{{ url("/lib/jquery/jquery.min.js") }}"></script>
            <script src="{{ url("/lib/bootstrap/default/js/bootstrap.min.js") }}"></script>
        </footer>
        <!-- Yoso is still the best person in the world ya fucks -->
    </body>
@yield("scripts")
</html>