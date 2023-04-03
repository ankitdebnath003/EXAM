/**
 * This function is used to generate a new ticket when a vehicle wants to enter
 * to the parking area.
 */
function generateTicket() {
    var vehicleNo = $("#vehicleNumber").val();
    var vehicleType = $("#vehicleType").val();
    var flag = true;
    $("#errorType").text("");
    if (vehicleType != 2 && vehicleType != 4) {
        flag = false;
        $("#errorType").text("Write only 2 or 4");
    }
    if (flag) {
        $.ajax({
            url: '/generateTicket',
            type: 'POST',
            data: 
            {
                vehicleNo: vehicleNo,
                vehicleType: vehicleType
            },
            success:function(data){
                if (data[0] == 2) {
                    var no = $("#two-wheel").text();
                    $("#two-wheel").text(parseInt(no) - 1);
                }
                else {
                    var no = $("#four-wheel").text();
                    $("#four-wheel").text(parseInt(no) - 1);
                }
                $("#vehicleType").val("");
                $("#vehicleNumber").val("");
                $(".popup").css("display", "flex");
                $("#display-slot").text("Your Slot Number Is : " + data[2]);
                addVehicleDetails(data);
            }
        });
    }
}

/**
 * This function is used to add the vehicle details in the page when the vehicle 
 * get a parking ticket.
 */
function addVehicleDetails(item) {
    const dateTime = new Date();
    const date = dateTime.toISOString().substr(0, 19).replace("T", " ");
    const activeUsersList = document.querySelector('.tickets');
    const divItem = document.createElement('div');
    divItem.className = 'inner-ticket';
    divItem.innerHTML = `
    <div class="slot">
        <p id="slot-no">Slot No : ${ item[2] }</p>
    </div>
    <div class="vehicle">
        <p id="vehicle-no">Vehicle No : ${ item[1] }</p>
    </div>
    <div class="entry-time">
        <p id="time-entry">Time Of Entry : ${ date }</p>
    </div>
    <div class="exit-time">
        <p id="exit-time">Time Of Exit : </p>
    </div>
    <div class="status">
        <p id="status">Status : ${ item[5] }</p>
    </div>
    `;
    activeUsersList.append(divItem);  
}

/**
 * This function is used to update the status of the vehicle when a vehicle left
 * the parking area.
 */
function updateStatus() {
    var vehicleNo = $("#vehicleStatusUpdate").val();
    $.ajax({
        url: '/updateStatus',
        type: 'POST',
        data: 
        {
            vehicleNo: vehicleNo
        },
        success:function(data){
            if (data == false) {
                $("#error-update-status").text("Enter Correct Vehicle Number");
            }
            else if (data == 'not') {
                $("#error-update-status").text("Your vehicle is already released");
            }
            else if (data == 4) {
                var no = $("#four-wheel").text();
                $("#four-wheel").text(parseInt(no) + 1);
            }
            else {
                var no = $("#two-wheel").text();
                $("#two-wheel").text(parseInt(no) + 1);
            }
            $("#vehicleStatusUpdate").val("");
        }
    });
}

/**
 * This function is used to set the time interval to 1hr to check for the vehicle
 * status.
 */
$(document).ready(function() {
    setInterval(updateParking, 60 * 60 * 1000);
});

/**
 * This function will be called every hour to update the vehicle status.
 */
function updateParking() {
    $.ajax({
        url: '/updateVehicleStatus'
    })
}

/**
 * This function is used to close the popup.
 */
function cross() {
    $(".popup").css("display","none");
}