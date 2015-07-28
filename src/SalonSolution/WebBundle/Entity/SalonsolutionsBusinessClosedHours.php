<?php

namespace SalonSolution\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsBusinessClosedHours
 *
 * @ORM\Table(name="salonSolutions_business_closed_hours")
 * @ORM\Entity
 */
class SalonsolutionsBusinessClosedHours
{
    /**
     * @var integer
     *
     * @ORM\Column(name="salon_id", type="integer", nullable=true)
     */
    public $salonId;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=true)
     */
    public $date;

    /**
     * @var string
     *
     * @ORM\Column(name="closed_from_time", type="string", length=255, nullable=true)
     */
    public $closedFromTime;

    /**
     * @var string
     *
     * @ORM\Column(name="closed_to_time", type="string", length=255, nullable=true)
     */
    public $closedToTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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