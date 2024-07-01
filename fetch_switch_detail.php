<?php
session_Start();
// header('Content-Type: text/html; charset=utf-8');
$flag = 0;
$device_ip = (isset($_GET['device_ip']) ? $_GET['device_ip']:'');
$community = (isset($_GET['community']) ? $_GET['community']:'');

$oid_ifDescr = '1.3.6.1.2.1.2.2.1.2';
$oid_ifAlias = '1.3.6.1.2.1.31.1.1.1.18';
$oid_ifIndex = '1.3.6.1.2.1.2.2.1.1';
$oid_sys_name = '1.3.6.1.2.1.1.5.0';

$ifDescrs   =  @snmpwalk($device_ip, $community, $oid_ifDescr);
$ifAliases  =  @snmpwalk($device_ip, $community, $oid_ifAlias);
$ifIndices  =  @snmpwalk($device_ip, $community, $oid_ifIndex);
$deviceName =  @snmpget($device_ip, $community, $oid_sys_name);

if ($ifDescrs === false || $ifAliases === false || $ifIndices === false || $deviceName === false) {
     $_SESSION['message'] = "Failed to Fetch SNMP Data.";
        $_SESSION['message_type'] = "error";
        header("Location: front_page.php"); 
    exit; // Terminate script execution
}

$ifIndices = preg_replace('/^.*: /', '', $ifIndices);

function clean_snmp_value($value) {
    return trim(str_replace('STRING:', '', $value));
}


function clean_alias($alias) {
    return trim($alias, '*');
}

function clean_port($value){
    return trim(str_replace('ge-0/0/','',$value));
}


$interfaces = [];
$deviceName = clean_snmp_value($deviceName);

foreach ($ifDescrs as $index => $ifDescr) {
    $ifDescrClean = clean_snmp_value($ifDescr);
    $ifAliasClean = clean_snmp_value($ifAliases[$index]);
    $ifIndexClean = clean_snmp_value($ifIndices[$index]);

    if (!empty($ifAliasClean) && strpos($ifAliasClean, '*****') !== false) {
        $ifAliasClean = clean_alias($ifAliasClean);
        $ifDescrClean= clean_port($ifDescrClean);

        $interfaces[] = [
            'ifIndex' => $ifIndexClean,
            'ifDescr' => $ifDescrClean,
            'ifAlias' => $ifAliasClean,
            'device_ip' => $device_ip,
            'community' => $community
        ];
    }
}


// echo json_encode([
//     'device_name' => $deviceName,
//     'interfaces' => $interfaces
// ]);
// Example: Save to database if not already exists (replace with your database connection and query)
try {
    // Replace with your database connection details
    $pdo = new PDO('mysql:host=localhost;dbname=switch_details', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if data already exists
    foreach ($interfaces as $interface) {
        // echo "Checking interface: " . json_encode($interface) . "\n";
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

        // foreach ($interfaces as $interface) {
            $stmt->execute([
                'device_ip' => $interface['device_ip'],
                'ifIndex' => $interface['ifIndex'],
                'ifDescr' => $interface['ifDescr'],
                'ifAlias' => $interface['ifAlias'],
                'community' => $interface['community']
            ]);
        
        // echo "Inserted interface: " . json_encode($interface) . "\n"
        $flag = 1;
    }}
    if ($flag === 1) {
        $_SESSION['message'] = "Detail inserted successfully!!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "No data inserted";
        $_SESSION['message_type'] = "error";
    }

    header("Location: front_page.php"); // Redirect back to front_page.php

} catch (PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
    $_SESSION['message_type'] = "error";
    header("Location: front_page.php"); // Redirect back to front_page.php
    exit;
}


// Respond with JSON data
// echo json_encode([
//     'device_name' => clean_snmp_value($deviceName),
//     'interfaces' => $interfaces
// ]);
?>
