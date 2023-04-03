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
            url: 'GenerateTicket.php',
            type: 'POST',
            data: 
            {
                vehicleNo: vehicleNo,
                vehicleType: vehicleType
            },
            success:function(data){
                $("#vehicleType").val("");
                $("#vehicleNumber").val("");
                $(".popup").css("display", "flex");
                $("#display-slot").text("Your Slot Number Is : " + data);
            }
        });
    }
}

function updateStatus() {
    var vehicleNo = $("#vehicleStatusUpdate").val();
    $.ajax({
        url: 'UpdateStatus.php',
        type: 'POST',
        data: 
        {
            vehicleNo: vehicleNo
        },
        success:function(data){
            $("#vehicleNumber").val("");
        }
    });
}