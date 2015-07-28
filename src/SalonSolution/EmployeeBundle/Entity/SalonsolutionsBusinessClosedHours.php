<?php

namespace SalonSolution\EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsBusinessClosedHours
 */
class SalonsolutionsBusinessClosedHours
{
    /**
     * @var integer
     */
    public $salonId;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $closedFromTime;

    /**
     * @var string
     */
    public $closedToTime;

    /**
     * @var integer
     */
    public $id;


    /**
     * Set salonId
     *
     * @param integer $salonId
     * @return SalonsolutionsBusinessClosedHours
     */
    public function setSalonId($salonId)
    {
        $this->salonId = $salonId;
    
        return $this;
    }

    /**
     * Get salonId
     *
     * @return integer 
     */
    public function getSalonId()
    {
        return $this->salonId;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return SalonsolutionsBusinessClosedHours
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
     * Set closedFromTime
     *
     * @param string $closedFromTime
     * @return SalonsolutionsBusinessClosedHours
     */
    public function setClosedFromTime($closedFromTime)
    {
        $this->closedFromTime = $closedFromTime;
    
        return $this;
    }

    /**
     * Get closedFromTime
     *
     * @return string 
     */
    public function getClosedFromTime()
    {
        return $this->closedFromTime;
    }

    /**
     * Set closedToTime
     *
     * @param string $closedToTime
     * @return SalonsolutionsBusinessClosedHours
     */
    public function setClosedToTime($closedToTime)
    {
        $this->closedToTime = $closedToTime;
    
        return $this;
    }

    /**
     * Get closedToTime
     *
     * @return string 
     */
    public function getClosedToTime()
    {
        return $this->closedToTime;
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
