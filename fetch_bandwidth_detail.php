<?php
function getBandwidthUsage($community, $switch_ip, $interface)
{
    $oidIn = "1.3.6.1.2.1.2.2.1.10.$interface";
    $oidOut = "1.3.6.1.2.1.2.2.1.16.$interface";
    $oidAlias = "1.3.6.1.2.1.31.1.1.1.18.$interface";
    $oidDeviceName = "1.3.6.1.2.1.1.5.0";
    $oidPortDescr = "1.3.6.1.2.1.2.2.1.2.$interface";
    // $band = "1.3.6.1.2.1.31.1.1.1.19.$interface";
    // $oidBandwidthLimit = "1.3.6.1.2.1.31.1.1.1.15.$interface"; 

    // Perform SNMP get request
    // $bandwidthLimit = snmpget($switch_ip, $community, $oidBandwidthLimit);

    // Clean SNMP value
    // $bandwidthLimit = preg_replace('/^.*: /', '', $bandwidthLimit);
    // $bandwidthLimit = intval($bandwidthLimit)/1000000;
    // $oidAlias = "1.3.6.1.2.1.31.1.1.1.18.$interface";

    $alias = snmpget($switch_ip, $community, $oidAlias);
    $deviceName = snmpget($switch_ip, $community, $oidDeviceName);
    $portDescr = snmpget($switch_ip, $community, $oidPortDescr);
    // $band = snmpget($switch_ip, $community, $bandwidthLimit);

    $alias = preg_replace('/^.*: /', '', $alias);
    $deviceName = preg_replace('/^.*: /', '', $deviceName);
    $portDescr = preg_replace('/^.*: /', '', $portDescr);

    $in1 = snmp2_get($switch_ip, $community, $oidIn);
    $out1 = snmp2_get($switch_ip, $community, $oidOut);
    // $alias = snmpget($switch_ip, $community, $oidAlias);

    $in1 = preg_replace('/^.*: /', '', $in1);
    $in1 = intval($in1);
    $out1 = preg_replace('/^.*: /', '', $out1);
    $out1 = intval($out1);
    // $alias = preg_replace('/^.*: /', '', $alias);

    sleep(10);

    $in2 = snmp2_get($switch_ip, $community, $oidIn);
    $out2 = snmp2_get($switch_ip, $community, $oidOut);
    // $alias = snmpget($switch_ip, $community, $oidAlias);

    $in2 = preg_replace('/^.*: /', '', $in2);
    $in2 = intval($in2);
    $out2 = preg_replace('/^.*: /', '', $out2);
    $out2 = intval($out2);

    $in = abs($in2 - $in1);
    $out = abs($out2 - $out1);

    $inBandwidth = ($in * 8) / (10 * 1000000); // Mbps
    $outBandwidth = ($out * 8) / (10 * 1000000); // Mbps
    $totalBandwidth = $inBandwidth + $outBandwidth;

    return [
        // 'interface' => $interface,
        'alias' => $alias,
        'port' => $portDescr,
        'deviceName' => $deviceName,
        'inBandwidth' => $inBandwidth,
        'outBandwidth' => $outBandwidth,
        'totalBandwidth' => $totalBandwidth,
        // 'band' => $bandwidthLimit,
        'timestamp' => time()
    ];
}



if (isset($_GET['ifIndex']) && isset($_GET['community']) && isset($_GET['device_ip'])) {
    $ifIndex = $_GET['ifIndex'];
    $switch_ip = $_GET['device_ip'];
    $community = $_GET['community']; 

    if (isset($_GET['api'])) {
        header('Content-Type: application/json');
        $bandwidth = getBandwidthUsage($community, $switch_ip, $ifIndex);
        echo json_encode($bandwidth);
        exit;
    }
} else {
    echo "Required parameters are missing.";
}