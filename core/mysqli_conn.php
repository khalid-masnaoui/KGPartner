<?php

//resources server database
$DB2_HOST = '42.127.255.19'; // set database host
$DB2_USER = 'koreaGaming'; // set database user
$DB2_PASS = 'k1sol#U24u43wa'; // set database password
$DB2_NAME = 'k1api'; // set database name
// Create connection
$conn2 = new mysqli($DB2_HOST, $DB2_USER, $DB2_PASS, $DB2_NAME);
if ($conn2->connect_error) {
    $fileLogger = new FileLogger(__DIR__ . '/../logs/database.log');
    $fileLogger->log("Connection failed: " . $conn2->connect_error, FileLogger::FATAL);
}

?>
