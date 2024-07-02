<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandwidth Monitoring</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script> <!--for date and time manipulation.-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!--for creating charts.-->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script> <!--Adapter to use Moment.js with Chart.js.-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> <!--Includes html2canvas for taking screenshots of web content..-->
    <style>
        * {
            background-color: lightblue;
        }

        canvas {
            background-color: wheat;
            /* color:black; */
            margin-left: 150px;
            margin-top: 40px;
            margin-bottom: 150px;
            margin-right: 150px;
        }

        p {
            background-color: white;
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

        #download-screenshot {
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

        #loading {
            display: none;
            /* Initially hide loading spinner */
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: black;
            background-color: wheat;
        }

        #loading-spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin-bottom: 20px;
            background-color: wheat;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <button id="download-screenshot">Download Screenshot</button>
    <div id="content">
        <div id="loading">
            loading...
            <div id="loading-spinner"></div>
        </div>
        <p id="interfaceDetails" class="info"></p>

        <canvas id="bandwidthChart"></canvas>
        <script>
            document.getElementById('download-screenshot').addEventListener('click', function() {
                html2canvas(document.querySelector('#content')).then(function(canvas) {
                    let link = document.createElement('a');
                    link.download = 'screenshot.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        </script>

        <!-- Initializes a Chart.js line chart with empty data -->

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


            // Retrieves data from sessionStorage 

            const deviceIp = sessionStorage.getItem('device_ip');
            const community = sessionStorage.getItem('community');
            const ifIndex = sessionStorage.getItem('ifIndex');

            //Historical data can be store in database for persistance (future scope)

            // function fetchHistoricalData() {
            //     showLoading();
            //     var apiUrl = `fetch_bandwidth_detail.php?historical=1&ifIndex=${ifIndex}&community=${community}&device_ip=${deviceIp}`;
            //     fetch(apiUrl)
            //         .then(response => response.json())
            //         .then(data => {

            //             data.forEach(dataPoint => {
            //                 var timestamp = new Date(dataPoint.timestamp * 1000);
            //                 bandwidthChart.data.labels.push(timestamp);
            //                 bandwidthChart.data.datasets[0].data.push(dataPoint.inBandwidth);
            //                 bandwidthChart.data.datasets[1].data.push(dataPoint.outBandwidth);
            //                 bandwidthChart.data.datasets[2].data.push(dataPoint.totalBandwidth);
            //             });
            //             bandwidthChart.update();
            //         })
            //         .catch(error => console.error('Error fetching historical data:', error));
            // }

            showLoading();

            // fetchRealTimeData(): Fetches real-time data and updates the chart

            function fetchRealTimeData() {
                var apiUrl = `fetch_bandwidth_detail.php?api=1&ifIndex=${ifIndex}&community=${community}&device_ip=${deviceIp}`;
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('interfaceDetails').innerHTML = `${data.alias} : (${data.port})`;
                        hideLoading();
                        var now = new Date(data.timestamp * 1000);
                        bandwidthChart.data.labels.push(now);
                        bandwidthChart.data.datasets[0].data.push(data.inBandwidth);
                        bandwidthChart.data.datasets[1].data.push(data.outBandwidth);
                        bandwidthChart.data.datasets[2].data.push(data.totalBandwidth);

                        if (bandwidthChart.data.labels.length > 90) {              //for 15min data
                            bandwidthChart.data.labels.shift();
                            bandwidthChart.data.datasets.forEach(dataset => {
                                dataset.data.shift();
                            });
                        }

                        bandwidthChart.update();
                    })
                    .catch(error => console.error('Error fetching real-time data:', error));
            }


            function showLoading() {
                document.getElementById('loading').style.display = 'block';
            }

            function hideLoading() {
                document.getElementById('loading').style.display = 'none';
            }

            // fetchHistoricalData();

            setInterval(fetchRealTimeData, 10000); // set interval to fetch data in 10 sec
        </script>
    </div>
</body>

</html>