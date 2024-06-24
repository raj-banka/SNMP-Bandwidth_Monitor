<?php
header('Content-Type: application/json');
$community = 'Stpi@123';
$device_ip = $_GET['host'];
// $device_ip = '203.129.217.66';
// var_dump($device_ip);

$oid_ifDescr = '1.3.6.1.2.1.2.2.1.2';
$oid_ifAlias = '1.3.6.1.2.1.31.1.1.1.18';
$oid_ifIndex = '1.3.6.1.2.1.2.2.1.1';
$oid_sys_name = '1.3.6.1.2.1.1.5.0';

$ifDescrs = @snmpwalk($device_ip, $community, $oid_ifDescr);
$ifAliases = @snmpwalk($device_ip, $community, $oid_ifAlias);
$ifIndices = @snmpwalk($device_ip, $community, $oid_ifIndex);
$deviceName = @snmpget($device_ip, $community, $oid_sys_name);

if ($ifDescrs === false || $ifAliases === false || $ifIndices === false || $deviceName === false) {
    echo json_encode(["error" => "Failed to fetch SNMP data."]);
    exit; // Terminate script execution
}

$ifIndices = preg_replace('/^.*: /', '', $ifIndices);

function clean_snmp_value($value) {
    return trim(str_replace('STRING:', '', $value));
}

$interfaces = [];
$deviceName = clean_snmp_value($deviceName);

foreach ($ifDescrs as $index => $ifDescr) {
    $ifDescrClean = clean_snmp_value($ifDescr);
    $ifAliasClean = clean_snmp_value($ifAliases[$index]);
    $ifIndexClean = clean_snmp_value($ifIndices[$index]);

    if ($ifAliasClean !== '""')  {
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
?>