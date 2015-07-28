<?php

namespace SalonSolution\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsServiceAvailability
 *
 * @ORM\Table(name="salonSolutions_service_availability")
 * @ORM\Entity
 */
class SalonsolutionsServiceAvailability
{
    /**
     * @var integer
     *
     * @ORM\Column(name="service_id", type="integer", nullable=true)
     */
    public $serviceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_beds", type="integer", nullable=true)
     */
    public $totalBeds;

    /**
     * @var integer
     *
     * @ORM\Column(name="booked_beds", type="integer", nullable=true)
     */
    public $bookedBeds;

    /**
     * @var integer
     *
     * @ORM\Column(name="available_beds", type="integer", nullable=true)
     */
    public $availableBeds;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=true)
     */
    public $date;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=255, nullable=true)
     */
    public $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="creator_id", type="integer", nullable=true)
     */
    public $creatorId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_datetime", type="datetime", nullable=true)
     */
    public $creationDatetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="modifier_id", type="integer", nullable=true)
     */
    public $modifierId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modification_datetime", type="datetime", nullable=true)
     */
    public $modificationDatetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;



    /**
     * Set serviceId
     *
     * @param integer $serviceId
     * @return SalonsolutionsServiceAvailability
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    
        return $this;
    }

    /**
     * Get serviceId
     *
     * @return integer 
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Set totalBeds
     *
     * @param integer $totalBeds
     * @return SalonsolutionsServiceAvailability
     */
    public function setTotalBeds($totalBeds)
    {
        $this->totalBeds = $totalBeds;
    
        return $this;
    }

    /**
     * Get totalBeds
     *
     * @return integer 
     */
    public function getTotalBeds()
    {
        return $this->totalBeds;
    }

    /**
     * Set bookedBeds
     *
     * @param integer $bookedBeds
     * @return SalonsolutionsServiceAvailability
     */
    public function setBookedBeds($bookedBeds)
    {
        $this->bookedBeds = $bookedBeds;
    
        return $this;
    }

    /**
     * Get bookedBeds
     *
     * @return integer 
     */
    public function getBookedBeds()
    {
        return $this->bookedBeds;
    }

    /**
     * Set availableBeds
     *
     * @param integer $availableBeds
     * @return SalonsolutionsServiceAvailability
     */
    public function setAvailableBeds($availableBeds)
    {
        $this->availableBeds = $availableBeds;
    
        return $this;
    }

    /**
     * Get availableBeds
     *
     * @return integer 
     */
    public function getAvailableBeds()
    {
        return $this->availableBeds;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return SalonsolutionsServiceAvailability
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param string $time
     * @return SalonsolutionsServiceAvailability
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set creatorId
     *
     * @param integer $creatorId
     * @return SalonsolutionsServiceAvailability
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;
    
        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set creationDatetime
     *
     * @param \DateTime $creationDatetime
     * @return SalonsolutionsServiceAvailability
     */
    public function setCreationDatetime($creationDatetime)
    {
        $this->creationDatetime = $creationDatetime;
    
        return $this;
    }

    /**
     * Get creationDatetime
     *
     * @return \DateTime 
     */
    public function getCreationDatetime()
    {
        return $this->creationDatetime;
    }

    /**
     * Set modifierId
     *
     * @param integer $modifierId
     * @return SalonsolutionsServiceAvailability
     */
    public function setModifierId($modifierId)
    {
        $this->modifierId = $modifierId;
    
        return $this;
    }

    /**
     * Get modifierId
     *
     * @return integer 
     */
    public function getModifierId()
    {
        return $this->modifierId;
    }

    /**
     * Set modificationDatetime
     *
     * @param \DateTime $modificationDatetime
     * @return SalonsolutionsServiceAvailability
     */
    public function setModificationDatetime($modificationDatetime)
    {
        $this->modificationDatetime = $modificationDatetime;
    
        return $this;
    }

    /**
     * Get modificationDatetime
     *
     * @return \DateTime 
     */
    public function getModificationDatetime()
    {
        return $this->modificationDatetime;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}