<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandwidth Monitoring</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            background-color: blue;
        }

        canvas {
            background-color: aqua;
            margin-left: 150px;
            margin-top: 40px;
            margin-bottom: 150px;
            margin-right: 150px;
        }
        p{
            background-color: red;
            color: black;
            margin-left: 380px;
            margin-bottom: -30px;
            width: 30%;
            height: 25px;
            padding-left: 150px;
            padding-top: 10px;
            font-weight: 600;
            justify-content: space-around;
        }
        #download-screenshot{
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 86%;
        }
        #download-screenshot:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

<!-- <h2 id="deviceName" class="info"></h2> -->
<button id="download-screenshot">Download Screenshot</button>
<div id="content">
 <p id="interfaceDetails" class="info"></p>
    <!-- <h2 style="margin-left:50px;" id="interfaceAlias">Loading...</h2> -->
    <canvas id="bandwidthChart"></canvas>
    <script>
        document.getElementById('download-screenshot').addEventListener('click', function () {
            html2canvas(document.querySelector('#content')).then(function (canvas) {
                let link = document.createElement('a');
                link.download = 'screenshot.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });
    </script>

    <script>
        var ctx = document.getElementById('bandwidthChart').getContext('2d');
        var bandwidthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                        label: 'Incoming Bandwidth (Mbps)',
                        borderColor: 'green',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        data: [],
                        fill: false
                    },
                    {
                        label: 'Outgoing Bandwidth (Mbps)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        data: [],
                        fill: false
                    },
                    {
                        label: 'Total Bandwidth (Mbps)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        data: [],
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'minute'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        const deviceIp = sessionStorage.getItem('device_ip');
        const community = sessionStorage.getItem('community');
        const ifIndex = sessionStorage.getItem('ifIndex');
       

        function fetchHistoricalData() {
            var apiUrl = `fetch_bandwidth_detail.php?historical=1&ifIndex=${ifIndex}&community=${community}&device_ip=${deviceIp}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                   
                    data.forEach(dataPoint => {
                        var timestamp = new Date(dataPoint.timestamp * 1000);
                        bandwidthChart.data.labels.push(timestamp);
                        bandwidthChart.data.datasets[0].data.push(dataPoint.inBandwidth);
                        bandwidthChart.data.datasets[1].data.push(dataPoint.outBandwidth);
                        bandwidthChart.data.datasets[2].data.push(dataPoint.totalBandwidth);
                    });
                    bandwidthChart.update();
                })
                .catch(error => console.error('Error fetching historical data:', error));
        }

        function fetchRealTimeData() {
            var apiUrl = `fetch_bandwidth_detail.php?api=1&ifIndex=${ifIndex}&community=${community}&device_ip=${deviceIp}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // document.getElementById('deviceName').innerHTML = `Name: ${data.alias}`;
                    document.getElementById('interfaceDetails').innerHTML = `${data.alias} : (${data.port})`;
                    var now = new Date(data.timestamp * 1000);
                    bandwidthChart.data.labels.push(now);
                    bandwidthChart.data.datasets[0].data.push(data.inBandwidth);
                    bandwidthChart.data.datasets[1].data.push(data.outBandwidth);
                    bandwidthChart.data.datasets[2].data.push(data.totalBandwidth);

                    if (bandwidthChart.data.labels.length > 100) {
                        bandwidthChart.data.labels.shift();
                        bandwidthChart.data.datasets.forEach(dataset => {
                            dataset.data.shift();
                        });
                    }

                    bandwidthChart.update();
                })
                .catch(error => console.error('Error fetching real-time data:', error));
        }

        fetchHistoricalData();

        setInterval(fetchRealTimeData, 10000); // 1 minutes in milliseconds
    </script>
</div>
</body>

</html>