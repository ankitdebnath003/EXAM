<?php

namespace App\Controller;

use App\Entity\Parking;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This Class is used to get all the vehicle details and generate a ticket when a
 * vehicle is going to park and also update ticket status when a vehicle left
 * the parking area.
 */
class MainController extends AbstractController
{    
    /**
     *   @var object
     *     Stores the object of EntityManagerInterface Class.
     */
    private $em;
    /**
     *   @var object
     *     Stores the object of Parking Entity Class to get values by query.
     */
    private $parking;
    /**
     *   @var object
     *     Stores the object of Parking Entity Class to set new values.
     */
    private $vehicle;
        
    /**
     * Constructor is used to initialize the EntityManagerInterface and Parking
     * Class object to the Class variables.
     *
     *   @param object $em
     *     Store the object of EntityManagerInterface Class.
     * 
     *   @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->parking = $em->getRepository(Parking::class);
        $this->vehicle = new Parking();
    }

    /**
     * This method is used to send the user to the main page of the controller.
     * 
     *   @Route("/main", name="main")
     *     When the user sets the route to main then this route is used.
     * 
     *   @return Response
     *     Return to the main page of the controller.
     */
    public function main(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * This function is used to get the vehicle details that are already parked
     * and the number of available parking slots for both two and four wheelers.
     *
     *   @Route("/", name="home")
     *     When the user give the path of virtual host then this route will be used.
     * 
     *   @return array
     *     The number of available parking slots and vehicle details.
     */
    public function getVehicleDetails() {
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $twoWheel = $this->parking->findBy(['VehicleType' => 2, 'Status' => 'booked']);
        $fourWheel = $this->parking->findBy(['VehicleType' => 4, 'Status' => 'booked']);
        $res = $this->parking->findAll();
        $allDetails = [];

        // Get only today's entry to show.
        foreach ($res as $item) {
            if ($item->getEntryTime()->format('Y-m-d') == $today) {
                $allDetails[] = $item;
            }
        }

        // Stores the number of two wheeler parking slots available.
        $twoWheelAvailable = 100 - count($twoWheel);
        // Stores the number of four wheeler parking slots available.
        $fourWheelAvailable = 100 - count($fourWheel);

        return $this->render('parking/index.html.twig', [
            'allDetails' => $allDetails,
            'twoWheelAvailable' => $twoWheelAvailable,
            'fourWheelAvailable' => $fourWheelAvailable
        ]);
    }
    
    /**
     * This function is used to generate a new ticket for a vehicle.
     *
     *   @Route("/generateTicket", name="generateTicket")
     *     This route is used to generate a new ticket for a vehicle when the 
     *     user wants to generate a ticket by giving the vehicle number and its type.
     * 
     *   @param object $rq
     *     Stores the object of Request Class.
     * 
     *   @return Response
     *     The vehicle details is returned to ajax so that we can update the
     *     available slots and add the details of the vehicle to the page without
     *     refreshing.
     */
    public function generateTicket(Request $rq) {
        $vehicleNo = $rq->get("vehicleNo");
        $vehicleType = $rq->get("vehicleType");
        $slots = [];
        $res = $this->parking->findBy(["VehicleType" => $vehicleType]);

        // Checking if there is no value then set the default value to 0.
        if (!$res) {
            $available = 0;
        }
        else {

            // Getting all the booked slots from where the empty slot will be chosen.
            foreach ($res as $item) {
                if ($item->getStatus() == 'booked') {
                    $slots[] = $item->getSlotNumber();
                }
            }

            // Checking which slot is available.
            for ($i = 0; $i < 100; $i++) {
                if (!in_array($i, $slots)) {
                    $available = $i;
                    break;
                }
            }
        }

        // Set the data of the vehicle to database.
        $this->vehicle->setAllDetails($vehicleType, $vehicleNo, $available);
        $this->em->persist($this->vehicle);
        $this->em->flush();

        // Storing all the values in an array to return it to the ajax.
        $vehicleDetails = [$vehicleType, $vehicleNo, $available, new \DateTime(), null, 'booked'];
        return new JsonResponse($vehicleDetails);
    }
    
    /**
     * This function is used to update status when a vehicle will leave the parking.
     *
     *   @Route("/updateStatus", name="updateStatus")
     *     This route is used to update the status of a ticket if the vehicle 
     *     left the parking area then the slot will be made available.
     * 
     *   @param object $rq
     *     Stores the object of Request Class.
     * 
     *   @return Response
     *     The type of vehicle is returned to ajax so that we can update the
     *     available slots.
     */
    public function updateStatus(Request $rq) {
        $vehicleNo = $rq->get('vehicleNo');
        $res = $this->parking->findOneBy(["VehicleNumber" => $vehicleNo]);
        if (!$res) {
            return new Response(FALSE);
        }
        elseif ($res->getStatus() != 'booked') {
            return new Response("not");
        }
        $res->setExitTime(new DateTime());
        $res->setStatus('released');
        $this->em->persist($res);
        $this->em->flush();
        return new Response($res->getVehicleType());
    }
    
    /**
     * This function is used to update vehicle status every hour. If a vehicle
     * is there after two hours of time then change the status of the vehicle
     * to released.
     *
     *   @Route("/updateVehicleStatus", name="updateVehicleStatus")
     *     This route is used to update the vehicle status every hour.
     * 
     *   @return Response
     *     Return that the check is done every hour.
     */
    public function updateVehicleStatus() {
        // Get the booked vehicles' information.
        $res = $this->parking->findBy(["Status" => 'booked']);
        if ($res) {

            // Check that the timestamp is more than two hours or not. If it is 
            // more than two hours then set the vehicle status to released.
            foreach ($res as $item) {
                $entryTime = $item->getEntryTime();
                $currentTime = new DateTime();
                $interval = $entryTime->diff($currentTime);
                if ($interval->h >= 2) {
                    $item->setStatus('released');
                    $this->em->persist($res);
                    $this->em->flush();
                }
            }
        }
        return new Response("Done");
    }
}
