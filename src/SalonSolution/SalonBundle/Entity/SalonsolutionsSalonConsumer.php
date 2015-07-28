<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsSalonConsumer
 */
class SalonsolutionsSalonConsumer
{
    /**
     * @var integer
     */
    public $salonOwnerId;

    /**
     * @var integer
     */
    public $consumerId;

    /**
     * @var integer
     */
    public $status;

    /**
     * @var \DateTime
     */
    public $creationDatetime;

    /**
     * @var integer
     */
    public $id;


    /**
     * Set salonOwnerId
     *
     * @param integer $salonOwnerId
     * @return SalonsolutionsSalonConsumer
     */
    public function setSalonOwnerId($salonOwnerId)
    {
        $this->salonOwnerId = $salonOwnerId;
    
        return $this;
    }

    /**
     * Get salonOwnerId
     *
     * @return integer 
     */
    public function getSalonOwnerId()
    {
        return $this->salonOwnerId;
    }

    /**
     * Set consumerId
     *
     * @param integer $consumerId
     * @return SalonsolutionsSalonConsumer
     */
    public function setConsumerId($consumerId)
    {
        $this->consumerId = $consumerId;
    
        return $this;
    }

    /**
     * Get consumerId
     *
     * @return integer 
     */
    public function getConsumerId()
    {
        return $this->consumerId;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsSalonConsumer
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set creationDatetime
     *
     * @param \DateTime $creationDatetime
     * @return SalonsolutionsSalonConsumer
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
