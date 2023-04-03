<?php

// This file is used to generate a ticket for new car.
include "Class/Functions.php";

$obj = new Functions();
$vehicleNo = $_POST["vehicleNo"];
$vehicleType = $_POST["vehicleType"];
$arr = $obj->generateTicket($vehicleNo, $vehicleType);
?>