<?php
session_Start();

$flag = 0;                   //used to check if any new data is iserted into database or not

$device_ip = (isset($_GET['device_ip']) ? $_GET['device_ip'] : '');     // fetch the device_ip from "add_device.php"
$community = (isset($_GET['community']) ? $_GET['community'] : '');     // fetch the community from "add_device.php"

$oid_ifDescr = '1.3.6.1.2.1.2.2.1.2';                       //oid for switch discription
$oid_ifAlias = '1.3.6.1.2.1.31.1.1.1.18';                   //oid for Alias given to switch eg "****infotech****
$oid_ifIndex = '1.3.6.1.2.1.2.2.1.1';                       //oid for ifindex of interface
$oid_sys_name = '1.3.6.1.2.1.1.5.0';                        //oid for device name

//SNMP commonds to fetch details of device

$ifDescrs   =  @snmpwalk($device_ip, $community, $oid_ifDescr);
$ifAliases  =  @snmpwalk($device_ip, $community, $oid_ifAlias);
$ifIndices  =  @snmpwalk($device_ip, $community, $oid_ifIndex);
$deviceName =  @snmpget($device_ip, $community, $oid_sys_name);

if ($ifDescrs === false || $ifAliases === false || $ifIndices === false || $deviceName === false) {
    $_SESSION['message'] = "Failed to Fetch SNMP Data.";          // Store data in Session
    $_SESSION['message_type'] = "error";
    header("Location: front_page.php");
    exit; // Terminate script execution
}

$ifIndices = preg_replace('/^.*: /', '', $ifIndices);          //filter the output

function clean_snmp_value($value)
{
    return trim(str_replace('STRING:', '', $value));
}


function clean_alias($alias)
{                                  //filter name to print without *
    return trim($alias, '*');
}

function clean_port($value)
{
    return trim(str_replace('ge-0/0/', '', $value));               // filter port number
}


$interfaces = [];
$deviceName = clean_snmp_value($deviceName);

foreach ($ifDescrs as $index => $ifDescr) {
    $ifDescrClean = clean_snmp_value($ifDescr);
    $ifAliasClean = clean_snmp_value($ifAliases[$index]);
    $ifIndexClean = clean_snmp_value($ifIndices[$index]);

    // if (!empty($ifAliasClean) && strpos($ifAliasClean, '*****') !== false) {

    if (!empty($ifAliasClean)) {
        $ifAliasClean = clean_alias($ifAliasClean);
        $ifDescrClean = clean_port($ifDescrClean);

        $interfaces[] = [
            'ifIndex' => $ifIndexClean,
            'ifDescr' => $ifDescrClean,
            'ifAlias' => $ifAliasClean,
            'device_ip' => $device_ip,
            'community' => $community
        ];
    }
}


try {
    // Database connection details

    $pdo = new PDO('mysql:host=localhost;dbname=switch_details', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if data already exists
    foreach ($interfaces as $interface) {

        //prepares an SQL statement with placeholders (:device_ip, :community, :ifIndex) for later execution.

        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM switch_details WHERE device_ip = :device_ip AND community = :community AND ifIndex = :ifIndex");

        $stmt_check->execute([
            'device_ip' => $interface['device_ip'],
            'community' => $interface['community'],
            'ifIndex' => $interface['ifIndex']
        ]);

        $exists = $stmt_check->fetchColumn();

        if (!$exists) {
            // Prepare statement to insert data
            $stmt = $pdo->prepare("INSERT INTO switch_details (device_ip, ifIndex, ifDescr, ifAlias, community) VALUES (:device_ip, :ifIndex, :ifDescr, :ifAlias, :community)");

            $stmt->execute([
                'device_ip' => $interface['device_ip'],
                'ifIndex' => $interface['ifIndex'],
                'ifDescr' => $interface['ifDescr'],
                'ifAlias' => $interface['ifAlias'],
                'community' => $interface['community']
            ]);

            $flag = 1;         //indicates new data is inserted
        }
    }

    if ($flag === 1) {
        $_SESSION['message'] = "Data inserted successfully !!!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "No new data to insert !!!";
        $_SESSION['message_type'] = "error";
    }

    header("Location: front_page.php");                         // Redirect back to front_page.php

} catch (PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
    $_SESSION['message_type'] = "error";
    header("Location: front_page.php"); // Redirect back to front_page.php
    exit;
}
