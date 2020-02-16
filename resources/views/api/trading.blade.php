<html>
<head>
    <title>{{ $resource }} Tracking</title>
    <link rel="stylesheet" href="{{ url("/lib/bootstrap/paper/paper.min.css") }}">
    <script src="{{ url("/lib/jquery/jquery.min.js") }}"></script>
</head>
<body>
<div class="container">
    <h1>Tracking {{ $resource }}</h1>
    <ul>

    </ul>
</div>
</body>
</html>
<script>
    checkPerms();
    checkShit();
    function checkPerms() {
        if(!("Notification" in window)) {
            alert("This browser doesn't support notifications");
        } else if (Notification.permission === "granted") {
            console.log("Notifications enabled");
        } else if (Notification.permission !== "deneid") {
            Notification.requestPermission(function(permission) {
                if (permission === "granted") {
                    console.log("Permission granted");
                }
            });
        }
    }

    function notify(message) {
        var nofitication = new Notification(message);
    }

    function checkShit() {
        $.getJSON("{{ url("/api/tradeTracker/".$nID."/".$resource) }}")
                .done(function(data) {
                    if (data.bool) {
                        $.getJSON("{{ url("/api/nation") }}/" + data.nID)
                                .done(function(nation) {
                                    $("ul").append("<li class=text-danger>" + Date() + " - "+ nation.leadername +" Undercut You</li>");
                                    notify(nation.leadername + " undercut you with {{ $resource }} ("+ data.price +") PPU");
                                    console.log(true);
                                });
                    }

                    else {
                        $("ul").append("<li>" + Date() + " - Not Undercut</li>");
                        console.log(false);
                    }
                });
    }

    /*function checkShit() {
        $.getJSON("https://politicsandwar.com/api/tradeprice/resource={{ $resource }}&key=".env("PW_API_KEY")")
                .done(function(data) {
                    if (data.lowestbuy.nationid !== {{ $nID }})
                        notify("Someone undercut you in {{ $resource }}");
                })
    }*/

    window.setInterval(function() {
        checkShit();
    }, 30000);
</script>