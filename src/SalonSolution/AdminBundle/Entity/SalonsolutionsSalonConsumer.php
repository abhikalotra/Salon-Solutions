<?php

namespace SalonSolution\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsSalonConsumer
 *
 * @ORM\Table(name="salonSolutions_salon_consumer")
 * @ORM\Entity
 */
class SalonsolutionsSalonConsumer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="salon_owner_id", type="integer", nullable=true)
     */
    public $salonOwnerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="consumer_id", type="integer", nullable=true)
     */
    public $consumerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_datetime", type="datetime", nullable=false)
     */
    public $creationDatetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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