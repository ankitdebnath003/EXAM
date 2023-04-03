<?php
require 'vendor/autoload.php';
/**
 * This class is used to get all the data from the database that how many slots 
 * are available for both 2 and 4 wheelers and all the details of the cars that 
 * have been already parked and show them in the page.
 */
class Functions {
    
    /**
     *   @var string
     *     Stores the name of the database.
     */
    private $databaseName;
    /**
     *   @var string
     *     Stores the name of the server.
     */
    private $serverName;
    /**
     *   @var string
     *     Stores the name of the user.
     */
    private $userName;    
    /**
     *   @var string
     *     Stores the password.
     */
    private $password;
    /**
     *   @var object
     *     Stores the object of database.
     */
    private $conn;

    /**
     * Constructor is used to get the database credentials and create the connection
     * with the database and set the connection object to class variable.
     *
     *   @return void
     *     Only create the database connection.
     */
    public function __construct()
    {
        $this->getDataBaseCredentials();
        try {
            // Connecting to mySQL server through PDO.
            $this->conn = new PDO("mysql:host=$this->serverName;dbname=$this->databaseName", $this->userName, $this->password);
            // Set the PDO error mode to exception.
            // So that if any error occurs we can handle it through try-catch.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * This function is used to get all the database credentials from .env file 
     * by using dotenv package and set the credentials to class variables so that
     * they can be used everywhere.
     * 
     *   @return void
     *     Only assign credentials to class variables.
     */
    private function getDataBaseCredentials() {
        $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
        $dotenv->load();
        $this->databaseName = $_ENV['database'];
        $this->serverName = $_ENV['servername'];
        $this->userName = $_ENV['user'];
        $this->password = $_ENV['password'];
    }
    
    /**
     * This function is used to get the vehicle details that are already parked
     * and the number of available parking slots for both two and four wheelers.
     *
     *   @return array
     *     The number of available parking slots and vehicle details.
     */
    public function getVehicleDetails() {
        $noOfTwoWheelerParked = 0;
        $noOfFourWheelerParked = 0;
        // Getting data from database.
        $qry = "SELECT * FROM parking";
        $st = $this->conn->prepare($qry);
        $st->execute();

        // Checking if any vehicle is parked or not for the current day.
        if ($st->rowCount() > 0) {
            $res = $st->fetchAll();
            foreach ($res as $row) {
                $arr[] = [
                    "Type" => $row["Vehicle_Type"],
                    "Number" => $row["Vehicle_Number"],
                    "Slot_Number" => $row["Slot_Number"],
                    "Entry" => $row["Entry_Time"],
                    "Exit" => $row["Exit_Time"],
                    "Status" => $row["Status"],
                ];
                if ($row['Vehicle_Type'] == 2) {
                    $noOfTwoWheelerParked++;
                }
                else {
                    $noOfFourWheelerParked++;
                }
            }
        }

        $allDetails = [
            "twoAvailable" => 100 - $noOfTwoWheelerParked,
            "fourAvailable" => 100 - $noOfFourWheelerParked,
            "vehicleDetails" => $arr
        ];

        return $allDetails;
    }
    
    /**
     * This function is used to generate a new ticket for a vehicle.
     *
     *   @param string $vehicleNo
     *     Stores the vehicle number.
     *   @param int $vehicleType
     *     Stores the vehicle type that is two or four wheeler.
     * 
     *   @return void
     */
    public function generateTicket(string $vehicleNo, int $vehicleType) {
        $qry = "SELECT Slot_Number FROM parking";
        $st = $this->conn->prepare($qry);
        $st->execute();
        $arr = [];
        if ($st->rowCount() > 0) {
            $res = $st->fetchAll();
            foreach ($res as $row) {
                $arr[] = $row["Slot_Number"];
            }
        }
        for ($i = 0; $i < 100; $i++) {
            if (!in_array($i, $arr)) {
                $available = $i;
                break;
            }
        }
        $qry = "INSERT INTO parking VALUES ('$vehicleType', '$vehicleNo', '$available', sysdate(), null, 'booked')";
        $this->conn->exec($qry);
        echo $available;
    }
    
    /**
     * This function is used to update the status of the vehicle when a vehicle
     * leaves the parking place and update the slot number.
     *
     *   @param string $vehicleNo
     *     Stores the vehicle number.
     * 
     *   @return void
     */
    public function updateStatus(string $vehicleNo) {
        $qry = "UPDATE parking SET Status='released' WHERE Vehicle_Number='$vehicleNo'";
        $this->conn->exec($qry);
    }
}


?>