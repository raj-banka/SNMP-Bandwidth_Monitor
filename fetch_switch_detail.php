<?php
header('Content-Type: application/json');
$community = 'Stpi@123';
// $device_ip = (isset($_GET['device_ip']) ? $_GET['device_ip']:'');
$device_ip = '203.129.217.70';
// $cache_file = "/tmp/snmp_cache_{$device_ip}.json";
// $cache_time = 60; // Cache for 60 seconds
// $device_ip = '203.129.217.66';
// var_dump($device_ip);
// if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
//     echo file_get_contents($cache_file);
//     exit;
// }

$oid_ifDescr = '1.3.6.1.2.1.2.2.1.2';
$oid_ifAlias = '1.3.6.1.2.1.31.1.1.1.18';
$oid_ifIndex = '1.3.6.1.2.1.2.2.1.1';
$oid_sys_name = '1.3.6.1.2.1.1.5.0';

$ifDescrs   =  @snmpwalk($device_ip, $community, $oid_ifDescr);
$ifAliases  =  @snmpwalk($device_ip, $community, $oid_ifAlias);
$ifIndices  =  @snmpwalk($device_ip, $community, $oid_ifIndex);
$deviceName =  @snmpget($device_ip, $community, $oid_sys_name);

if ($ifDescrs === false || $ifAliases === false || $ifIndices === false || $deviceName === false) {
    echo json_encode(["error" => "Failed to fetch SNMP data."]);
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

// function clean_port($port) {
//     return trim($port, 'ge-0/0/');
// }

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


echo json_encode([
    'device_name' => $deviceName,
    'interfaces' => $interfaces
]);
// Example: Save to database if not already exists (replace with your database connection and query)
try {
    // Replace with your database connection details
    $pdo = new PDO('mysql:host=localhost;dbname=switch_details', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if data already exists
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM switch_details WHERE device_ip = :device_ip AND community = :community AND ifIndex = :ifIndex");
    $stmt_check->execute([
        'device_ip' => $device_ip,
        'community' => $community,
        'ifIndex' => $ifIndexClean
    ]);
    $exists = $stmt_check->fetchColumn();

    if (!$exists) {
        // Prepare statement to insert data
        $stmt = $pdo->prepare("INSERT INTO switch_details (device_ip, ifIndex, ifDescr, ifAlias, community) VALUES (:device_ip, :ifIndex, :ifDescr, :ifAlias, :community)");

        foreach ($interfaces as $interface) {
            $stmt->execute([
                'device_ip' => $interface['device_ip'],
                'ifIndex' => $interface['ifIndex'],
                'ifDescr' => $interface['ifDescr'],
                'ifAlias' => $interface['ifAlias'],
                'community' => $interface['community']
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    exit;
}

// Respond with JSON data
// echo json_encode([
//     'device_name' => clean_snmp_value($deviceName),
//     'interfaces' => $interfaces
// ]);
?>
