<?php

namespace SalonSolution\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsPayment
 *
 * @ORM\Table(name="salonSolutions_payment")
 * @ORM\Entity
 */
class SalonsolutionsPayment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    public $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="appointment_id", type="integer", nullable=true)
     */
    public $appointmentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="salon_id", type="integer", nullable=true)
     */
    public $salonId;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=255, nullable=true)
     */
    public $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_date", type="string", length=100, nullable=true)
     */
    public $transactionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_time", type="string", length=100, nullable=true)
     */
    public $transactionTime;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_amount", type="string", length=255, nullable=true)
     */
    public $transactionAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_method_id", type="integer", nullable=true)
     */
    public $paymentMethodId;

    /**
     * @var string
     *
     * @ORM\Column(name="is_recurring", type="string", length=1, nullable=true)
     */
    public $isRecurring;

    /**
     * @var string
     *
     * @ORM\Column(name="recurring_period", type="string", length=50, nullable=true)
     */
    public $recurringPeriod;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    public $status;

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