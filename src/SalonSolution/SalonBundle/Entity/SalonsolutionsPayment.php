<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsPayment
 */
class SalonsolutionsPayment
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
    public $transactionId;

    /**
     * @var string
     */
    public $transactionDate;

    /**
     * @var string
     */
    public $transactionTime;

    /**
     * @var string
     */
    public $transactionAmount;

    /**
     * @var integer
     */
    public $paymentMethodId;

    /**
     * @var string
     */
    public $isRecurring;

    /**
     * @var string
     */
    public $recurringPeriod;

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
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
     * Set transactionId
     *
     * @param string $transactionId
     * @return SalonsolutionsPayment
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    
        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set transactionDate
     *
     * @param string $transactionDate
     * @return SalonsolutionsPayment
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
    
        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return string 
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set transactionTime
     *
     * @param string $transactionTime
     * @return SalonsolutionsPayment
     */
    public function setTransactionTime($transactionTime)
    {
        $this->transactionTime = $transactionTime;
    
        return $this;
    }

    /**
     * Get transactionTime
     *
     * @return string 
     */
    public function getTransactionTime()
    {
        return $this->transactionTime;
    }

    /**
     * Set transactionAmount
     *
     * @param string $transactionAmount
     * @return SalonsolutionsPayment
     */
    public function setTransactionAmount($transactionAmount)
    {
        $this->transactionAmount = $transactionAmount;
    
        return $this;
    }

    /**
     * Get transactionAmount
     *
     * @return string 
     */
    public function getTransactionAmount()
    {
        return $this->transactionAmount;
    }

    /**
     * Set paymentMethodId
     *
     * @param integer $paymentMethodId
     * @return SalonsolutionsPayment
     */
    public function setPaymentMethodId($paymentMethodId)
    {
        $this->paymentMethodId = $paymentMethodId;
    
        return $this;
    }

    /**
     * Get paymentMethodId
     *
     * @return integer 
     */
    public function getPaymentMethodId()
    {
        return $this->paymentMethodId;
    }

    /**
     * Set isRecurring
     *
     * @param string $isRecurring
     * @return SalonsolutionsPayment
     */
    public function setIsRecurring($isRecurring)
    {
        $this->isRecurring = $isRecurring;
    
        return $this;
    }

    /**
     * Get isRecurring
     *
     * @return string 
     */
    public function getIsRecurring()
    {
        return $this->isRecurring;
    }

    /**
     * Set recurringPeriod
     *
     * @param string $recurringPeriod
     * @return SalonsolutionsPayment
     */
    public function setRecurringPeriod($recurringPeriod)
    {
        $this->recurringPeriod = $recurringPeriod;
    
        return $this;
    }

    /**
     * Get recurringPeriod
     *
     * @return string 
     */
    public function getRecurringPeriod()
    {
        return $this->recurringPeriod;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
     * @return SalonsolutionsPayment
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
