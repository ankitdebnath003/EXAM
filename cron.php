// This files runs everyday at 12AM to update all the vehicle status to release.
$conn = new mysqli("localhost", "ankit", "@bcD8888", "innoraft");

// Retrieve data to update
$sql = "SELECT * FROM parking WHERE Status='booked'";
$result = $conn->query($sql);

// Update data
$sql = "UPDATE parking SET ExitTime = NOW(), Status='released' WHERE Status='booked'";
$conn->query($sql);

$conn->close();
