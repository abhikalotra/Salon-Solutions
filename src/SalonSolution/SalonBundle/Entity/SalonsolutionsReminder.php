<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsReminder
 */
class SalonsolutionsReminder
{
    /**
     * @var integer
     */
    public $userId;

    /**
     * @var integer
     */
    public $appointmentId;

    /**
     * @var integer
     */
    public $salonId;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $remDate;

    /**
     * @var string
     */
    public $remTime;

    /**
     * @var integer
     */
    public $status;

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
     * Set userId
     *
     * @param integer $userId
     * @return SalonsolutionsReminder
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set appointmentId
     *
     * @param integer $appointmentId
     * @return SalonsolutionsReminder
     */
    public function setAppointmentId($appointmentId)
    {
        $this->appointmentId = $appointmentId;
    
        return $this;
    }

    /**
     * Get appointmentId
     *
     * @return integer 
     */
    public function getAppointmentId()
    {
        return $this->appointmentId;
    }

    /**
     * Set salonId
     *
     * @param integer $salonId
     * @return SalonsolutionsReminder
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
     * Set title
     *
     * @param string $title
     * @return SalonsolutionsReminder
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SalonsolutionsReminder
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set remDate
     *
     * @param string $remDate
     * @return SalonsolutionsReminder
     */
    public function setRemDate($remDate)
    {
        $this->remDate = $remDate;
    
        return $this;
    }

    /**
     * Get remDate
     *
     * @return string 
     */
    public function getRemDate()
    {
        return $this->remDate;
    }

    /**
     * Set remTime
     *
     * @param string $remTime
     * @return SalonsolutionsReminder
     */
    public function setRemTime($remTime)
    {
        $this->remTime = $remTime;
    
        return $this;
    }

    /**
     * Get remTime
     *
     * @return string 
     */
    public function getRemTime()
    {
        return $this->remTime;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsReminder
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
     * Set creatorId
     *
     * @param integer $creatorId
     * @return SalonsolutionsReminder
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
     * @return SalonsolutionsReminder
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
     * @return SalonsolutionsReminder
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
     * @return SalonsolutionsReminder
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
