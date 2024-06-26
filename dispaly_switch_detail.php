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
        // Retrieve the device IP and community string from sessionStorage
        const device_ip = sessionStorage.getItem('device_ip');
        const community = sessionStorage.getItem('community');
        

        if (device_ip && community) {
            // Fetch the interface details from the server
            fetch(`fetch_switch_detail.php?host=${device_ip}&community=${community}`)
                .then(response => {
                    
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    return response.json();
                })
                
                .then(data => {
                    const detailsDiv = document.getElementById('details');
                    if (data.device_name) {
                        detailsDiv.innerHTML = `<h2>Name: ${data.device_name}</h2>`;

                        // Display interfaces if needed
                        if (data.interfaces) {
                            const interfacesDiv = document.createElement('div');
                            data.interfaces.forEach(iface => {
                                const ifaceDiv = document.createElement('div');
                                ifaceDiv.innerHTML = `<h3>${iface.ifDescr}: ${iface.ifAlias} (${iface.ifIndex})</h3>`;

                                // Make ifaceDiv clickable
                                ifaceDiv.style.cursor = 'pointer';
                                ifaceDiv.addEventListener('click', () => {
                                    sessionStorage.setItem('ifIndex', iface.ifIndex);
                                    window.location.href = `dispaly_bandwidth_graph.php`;
                                });

                                interfacesDiv.appendChild(ifaceDiv);
                            });
                            detailsDiv.appendChild(interfacesDiv);
                        }
                    } else if (data.error) {
                        detailsDiv.innerHTML = `Error: ${data.error}`;
                    } else {
                        detailsDiv.innerHTML = 'Unknown error occurred';
                    }
                })
                .catch(error => {
                    
                    const detailsDiv = document.getElementById('details');
                    detailsDiv.innerHTML = `Fetch error: ${error.message}`;
                });
        }


    </script>
    </div>
</body>

</html>