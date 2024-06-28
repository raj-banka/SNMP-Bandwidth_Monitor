<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Switch Details</title>
    <link rel="stylesheet" href="interface.css"> 

</head>
<body>
    <div class="Section_top">
    <h1 style="margin-left:-800px; color: red; background-color:yellow; display:inline;padding:5px;">Interface Details:</h1>
    <div id="details"> </div>
    <div class="content">
           <!-- /<h1><span>INTERFACE DETAILS:</span></h1> -->
            <!-- <a href="#">Welcome</a> -->
        </div>
    
    <script>
        // PHP code embedded within JavaScript to fetch data
        <?php
        // Database connection parameters
        $host = 'localhost';
        $dbname = 'switch_details';
        $username = 'root';
        $password = '';

        try {
            // Establish database connection
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch data from database
            $stmt = $pdo->prepare("SELECT * FROM switch_details WHERE device_ip = :device_ip AND community = :community");
            $stmt->execute([
                'device_ip' => isset($_GET['device_ip']) ? $_GET['device_ip'] : '',  // Assuming host parameter is passed via GET
                'community' => 'Stpi@123'
            ]);
            $interfaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "const interfaces = " . json_encode($interfaces) . ";";  // Pass PHP data to JavaScript
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        ?>

        // JavaScript to render fetched data
        const detailsDiv = document.getElementById('details');
        if (interfaces && interfaces.length > 0) {
            const interfacesDiv = document.createElement('div');
            interfaces.forEach(iface => {
                const ifaceDiv = document.createElement('div');
                ifaceDiv.innerHTML = `<h3>${iface.ifIndex}: ${iface.ifAlias} (${iface.ifDescr})</h3>`;
                ifaceDiv.style.cursor = 'pointer';
                ifaceDiv.addEventListener('click', () => {
                    sessionStorage.setItem('ifIndex', iface.ifIndex);
                    window.location.href = `dispaly_bandwidth_graph.php`;
                });
                interfacesDiv.appendChild(ifaceDiv);
            });
            detailsDiv.appendChild(interfacesDiv);
        } else {
            detailsDiv.innerHTML = 'No data found';
        }
    </script>
    </div>
</body>
</html>
