<?php
require_once 'config.php';

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($connection === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
