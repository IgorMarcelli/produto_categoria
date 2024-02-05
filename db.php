<?php
$server = "DESKTOP-VO0N48H";
$username = "Igor";
$password = "1234";
$database = "projeto_spot";

$conn = sqlsrv_connect($server, array(
    "Database" => $database,
    "Uid" => $username,
    "PWD" => $password,
    "CharacterSet" => "UTF-8"
));

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
