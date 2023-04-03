<?php

// This file is used to update the status of the vehicle.
include "Class/Functions.php";

$obj = new Functions();
$vehicleNo = $_POST["vehicleNo"];
$arr = $obj->updateStatus($vehicleNo);

?>