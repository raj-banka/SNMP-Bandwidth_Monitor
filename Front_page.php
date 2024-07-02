<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        * {
            background-color: aqua;
        }

        .header {
            display: flex;
            background-color: lightblue;
        }

        h1 {
            margin-left: 440px;
            color: blue;
            background-color: lightblue;
        }

        h2 {
            background-color: azure;
        }

        .pc {
            margin-top: 10px;
            background-color: azure;
            color: red;
            font-size: large;
            font-weight: 400;
            margin-left: 40%;
            display: inline-block;
        }

        p {
            color: blue;
            font-weight: 800;
            background-color: azure;
        }

        .detail {
            margin-top: 10px;
            text-align: center;
            width: 500px;
            height: 200px;
            border: 2px solid black;
            margin-left: 30%;
            background-color: azure;
            border-radius: 10px;
        }

        input {
            width: 200px;
            padding: 2px;
            background-color: azure;
            border: 2px solid black;
            border-radius: 5px;
        }

        #Subbtn {
            width: 110px;
            background-color: blue;
            border: none;
            outline: none;
            height: 35px;
            border-radius: 25px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 650;
            margin: 10px 0;
            cursor: pointer;
        }

        #Subbtn:hover {
            background-color: aqua;
            color: black;
        }

        #Subbtn.clicked {
            background-color: red;
        }

        img {
            width: 100px;
            margin-top: 10px;
            margin-left: 25px;
            margin-bottom: 10px;
            aspect-ratio: 5/4;
            border-radius: 50%;
        }

        #loading {
            display: none;
            /* Initially hide loading spinner */
            position: fixed;
            left: 50%;
            top: 70%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: black;
            /* background-color: wheat; */
        }

        #loading-spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin-bottom: 20px;
            /* background-color: wheat; */
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
    <script>
        function changeStyle() {
            var button = document.getElementById('Subbtn');
            button.classList.toggle('clicked');
        }

        function addDevice() {
            const community = document.querySelector('input[name="community"]').value;
            const deviceIp = document.querySelector('input[name="device_ip"]').value;

            window.location.href = `fetch_switch_detail.php?device_ip=${encodeURIComponent(deviceIp)}&community=${encodeURIComponent(community)}`;
        }

        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }

        function handleOnclick() {
            showLoading();
            addDevice();
            changeStyle();
        }
    </script>
</head>

<body>
    <div class="header">
        <img src="image.png" alt="logo">
        <h1>WELCOME</h1>
    </div>

    <div id="content">
        <div id="loading">
            loading...
            <div id="loading-spinner"></div>
        </div>
        <div class="pc">
            <?php
            // Check for session message

            if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                $message = $_SESSION['message'];
                $message_type = $_SESSION['message_type'];

                // Clear session message

                unset($_SESSION['message']);
                unset($_SESSION['message_type']);

                // Display message as a JavaScript alert popup
                echo "$message";
            }
            ?>
        </div>

        <div class="detail">
            <h2>Enter Details:</h2>
            <p>Enter IP Address of Device: <input type="text" name="device_ip" required></p>
            <p>Enter Community String of Device: <input type="text" name="community" required></p>
            <button type="button" class="Sub-btn" id="Subbtn" onclick="handleOnclick()">Add Interface</button>
        </div>

</body>

</html>