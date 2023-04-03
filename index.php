<?php
    include "Class/Functions.php";
    $obj = new Functions();
    $arr = $obj->getVehicleDetails();
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Parking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Roboto:wght@100;400&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link rel="stylesheet" href="./Css/style.css">
	<link rel="stylesheet" href="./Css/popup.css">
</head>

<body>

    <div class="popup">
        <div class="content">
            <input type="submit" onclick="cross()" class="closebtn" value="&times;">
            <p id="display-slot"></p>
        </div>
    </div>

    <div class="container">

        <!-- This section is used to show how many parking slots are available -->
        <section class="availability">
            <h1>Availability Of The Parking Slots</h1>
            <div class="two-wheeler">
                <h3>Two wheeler available : </h3>
                <p id="two-wheel"><?php echo $arr["twoAvailable"] ?></p>
            </div>
            <div class="four-wheeler">
                <h3>Four wheeler available : </h3>
                <p id="four-wheel"><?php echo $arr["fourAvailable"] ?></p>
            </div>
        </section>

        <!-- This section is used to show the details of booked parking slot -->
        <section class="tickets">
            <h1>Tickets Details For Today</h1>
            <?php 
            // This loop is used to show all the vehicle details that are parked.
            foreach ($arr["vehicleDetails"] as $vehicle) {
            ?>
                <div class="inner-ticket">
                    <div class="slot">
                        <p id="slot-no">Slot No : <?php echo $vehicle["Slot_Number"] ?></p>
                    </div>
                    <div class="vehicle">
                        <p id="vehicle-no">Vehicle No : <?php echo $vehicle["Number"] ?></p>
                    </div>
                    <div class="entry-time">
                        <p id="time-entry">Time Of Entry : <?php echo $vehicle["Entry"] ?></p>
                    </div>
                    <div class="exit-time">
                        <p id="exit-time">Time Of Exit : <?php echo $vehicle["Exit"] ?></p>
                    </div>
                    <div class="slot">
                        <p id="status">Status : <?php echo $vehicle["Status"] ?></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </section>

        <!-- This section is used to to generate a ticket based on the vehicle details -->
        <section class="generate-ticket">
            <!--creating a form for taking input for vehicle details -->
            <form method="post" onsubmit="return false;" class="container mt-5 ticket-generator-form">
                <h1>Please Enter Your Vehicle Details</h1>
                <div class="form-group my-4">
                    <label for="vehicleNumber">Vehicle Number :</label>
                    <input id="vehicleNumber" required="true" type="text" class="form-control" name="vehicleNumber">
                </div>
                <div class="form-group">
                    <label for="vehicleType">Type Of Vehicle(2/4) :</label>
                    <input id="vehicleType" required="true" type="number" class="form-control" name="vehicleType">
                </div>
                <p id="errorType" class="text-danger"></p>
                <input type="submit" class="mt-3 btn btn-primary" name="submit" onclick="generateTicket()"></input>
            </form>
        </section>

        <!-- This section is used to update the slot based on the vehicle status -->
        <section class="release-slot">
            <!--creating a form for taking input for updating the slot -->
            <form method="post" onsubmit="return false;" class="container mt-5 slot-update-form">
                <h1>Please Enter The Vehicle Number To Update The Vehicle Status</h1>
                <div class="form-group my-4">
                    <label for="vehicleNumber">Vehicle Number :</label>
                    <input id="vehicleStatusUpdate" required="true" type="text" class="form-control" name="vehicleNumber">
                </div>
                <input type="submit" class="mt-3 btn btn-primary" name="submit" onclick="updateStatus()"></input>
            </form>
        </section>        
    </div>

    <script src="./Js/script.js"></script>
    <script src="./Js/popup.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>  
</body>

</html>