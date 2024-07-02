<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="homepage1.css"> <!--included css file -->
    <title>Admin Page</title>
    <script>
        function fetchSwitchDetails(host, community) {
            window.location.href = `display_switch_detail.php?device_ip=${encodeURIComponent(host)}&community=${encodeURIComponent(community)}`;
            //set data and target to "display_switch_detail.php".
        }
    </script>
</head>

<body>
    <div class="Section_top">
        <header class="top">
            <div class="left_header"><img id="Logo" src="image.png" alt="logo"></div>
            <div class="right_header">
                <h1 id="heading">Monitoring Dashboard</h1>
            </div>
        </header>
        <div class="main">
            <div class="right">
                <h2>Check Switch Status</h2>
                <ul class="switches">
                    <li id="switch1" class="switch"><span class="switch_span"><a class="ank" href="#" onclick="fetchSwitchDetails('203.129.217.66','Stpi@123')">New NOC</a></span></li>
                    <li id="switch2" class="switch"><span class="switch_span"><a class="ank" href="#" onclick="fetchSwitchDetails('203.129.217.67','Stpi@123')">Old NOC</a></span></li>
                    <li id="switch3" class="switch"><span class="switch_span"><a class="ank" href="#" onclick="fetchSwitchDetails('203.129.217.70','Stpi@123')">New Build.</a></span></li>
                    <li id="add-interface" class="switch"><span class="switch_span"><a class="ank" href="Front_page.php">Add Interface </a></span></li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>