<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ App::environment('local') ? "(Dev)" : "" }}Banque Lumiere</title>
    <link rel="stylesheet" href="{{ url("/lib/bootstrap/paper/paper.min.css") }}">
    <link rel="stylesheet" href="{{ url("/css/default.css") }}">

    @yield("headScripts")
    </head>
    <body>
    <div class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
            <a href="{{ url("/") }}" class="navbar-brand"><img src="https://i.ibb.co/3R9N29G/imageedit-3-7630138711.png" width="70" height="50" title="Banque Lumiére Logo" alt="" /> Banque Lumiére{{ App::environment('local') ? "(Dev)" : "" }}</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav">
                   
                    <li><a href="{{ url("/accounts") }}">Accounts</a></li>              
                    <li><a href="{{ url("/ia/apply") }}">About</a></li>

                    <li><a href="{{ url("/ia/apply") }}"></a></li>



                    <?php
$t = date("i");

if ($t < "05") {
  echo '<li><a href="\"><strong><font color=\"E60000\">A turn is in progress!</strong> Transactions won\'t work. Time <u>' . date("h:i:s A") . '</u></font></a></li>';
} elseif ($t > "55") {
  echo '<li><a href="\"><strong><font color=\"E60000\">A turn is in progress!</strong> Transactions won\'t work. Time <u>' . date("h:i:s A") . '</u></font></a></li>';
} else {
  echo '<li><a href="\">RnCo Server Time <u>' . date("l jS \of F Y h:i:s A") . '</u></a></li>';
}
?>

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
                                <li><a href="{{ url('/user/dashboard') }}"><i class="fa fa-btn fa-sign-out"></i>Dashboard</a></li>
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

<a href="https://banque-lumiere.pro/admin" target="_blank">Control Panel</a> 
|
<a href="https://politicsandwar.com/alliance/id=7399" target="_blank">Banque Lumiére</a> 
|
<a href="https://politicsandwar.com/nation/id=49745" target="_blank">sui</a>


            </nav>

            <script src="{{ url("/lib/jquery/jquery.min.js") }}"></script>
            <script src="{{ url("/lib/bootstrap/default/js/bootstrap.min.js") }}"></script>
        </footer>
        <!-- Blackbird is still the best person in the world, ya fucks -->
    </body>
@yield("scripts")
</html>
