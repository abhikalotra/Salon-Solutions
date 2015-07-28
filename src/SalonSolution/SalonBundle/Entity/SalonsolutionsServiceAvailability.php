<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsServiceAvailability
 */
class SalonsolutionsServiceAvailability
{
    /**
     * @var integer
     */
    public $serviceId;

    /**
     * @var integer
     */
    public $totalBeds;

    /**
     * @var integer
     */
    public $bookedBeds;

    /**
     * @var integer
     */
    public $availableBeds;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $time;

    /**
     * @var integer
     */
    public $creatorId;

    /**
     * @var \DateTime
     */
    public $creationDatetime;

    /**
     * @var integer
     */
    public $modifierId;

    /**
     * @var \DateTime
     */
    public $modificationDatetime;

    /**
     * @var integer
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
