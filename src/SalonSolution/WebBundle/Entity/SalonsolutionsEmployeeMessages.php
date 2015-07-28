<?php

namespace SalonSolution\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsEmployeeMessages
 *
 * @ORM\Table(name="salonSolutions_employee_messages")
 * @ORM\Entity
 */
class SalonsolutionsEmployeeMessages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="salon_owner_id", type="integer", nullable=true)
     */
    public $salonOwnerId;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    public $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    public $status;

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
     * @return SalonsolutionsEmployeeMessages
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
     * Set type
     *
     * @param string $type
     * @return SalonsolutionsEmployeeMessages
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return SalonsolutionsEmployeeMessages
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsEmployeeMessages
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}