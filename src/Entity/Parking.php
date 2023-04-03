<?php

namespace App\Entity;

use App\Repository\ParkingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParkingRepository::class)]
class Parking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $VehicleType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $VehicleNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $SlotNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $EntryTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ExitTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicleType(): ?int
    {
        return $this->VehicleType;
    }

    public function setVehicleType(?int $VehicleType): self
    {
        $this->VehicleType = $VehicleType;

        return $this;
    }

    public function getVehicleNumber(): ?string
    {
        return $this->VehicleNumber;
    }

    public function setVehicleNumber(?string $VehicleNumber): self
    {
        $this->VehicleNumber = $VehicleNumber;

        return $this;
    }

    public function getSlotNumber(): ?int
    {
        return $this->SlotNumber;
    }

    public function setSlotNumber(?int $SlotNumber): self
    {
        $this->SlotNumber = $SlotNumber;

        return $this;
    }

    public function getEntryTime(): ?\DateTimeInterface
    {
        return $this->EntryTime;
    }

    public function setEntryTime(?\DateTimeInterface $EntryTime): self
    {
        $this->EntryTime = $EntryTime;

        return $this;
    }

    public function getExitTime(): ?\DateTimeInterface
    {
        return $this->ExitTime;
    }

    public function setExitTime(?\DateTimeInterface $ExitTime): self
    {
        $this->ExitTime = $ExitTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
    
    /**
     * This function sets all the vehicle information to database.
     *
     *   @param int $vehicleType
     *     Stores the vehicle type.
     *   @param string $vehicleNo
     *     Stores the vehicle number.
     *   @param int $available
     *     Stores the slot number.
     * 
     *   @return void
     */
    public function setAllDetails(int $vehicleType, string $vehicleNo, int $available) {
        $this->setVehicleType($vehicleType);
        $this->setVehicleNumber($vehicleNo);
        $this->setSlotNumber($available);
        $this->setEntryTime(new \DateTime());
        $this->setExitTime(null);
        $this->setStatus('booked');
    }
}
