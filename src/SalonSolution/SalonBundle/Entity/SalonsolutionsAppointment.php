<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsAppointment
 */
class SalonsolutionsAppointment
{
    /**
     * @var integer
     */
    public $consumerId;

    /**
     * @var integer
     */
    public $salonId;

    /**
     * @var integer
     */
    public $serviceId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $mobile;

    /**
     * @var string
     */
    public $landline;

    /**
     * @var string
     */
    public $photo;

    /**
     * @var string
     */
    public $scheduledDate;

    /**
     * @var string
     */
    public $scheduledTime;

    /**
     * @var integer
     */
    public $bookingType;

    /**
     * @var string
     */
    public $notes;

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
     * Set consumerId
     *
     * @param integer $consumerId
     * @return SalonsolutionsAppointment
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
     * Set salonId
     *
     * @param integer $salonId
     * @return SalonsolutionsAppointment
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
     * Set serviceId
     *
     * @param integer $serviceId
     * @return SalonsolutionsAppointment
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
     * Set firstName
     *
     * @param string $firstName
     * @return SalonsolutionsAppointment
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return SalonsolutionsAppointment
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return SalonsolutionsAppointment
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return SalonsolutionsAppointment
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return SalonsolutionsAppointment
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return SalonsolutionsAppointment
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return SalonsolutionsAppointment
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return SalonsolutionsAppointment
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return SalonsolutionsAppointment
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    
        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set landline
     *
     * @param string $landline
     * @return SalonsolutionsAppointment
     */
    public function setLandline($landline)
    {
        $this->landline = $landline;
    
        return $this;
    }

    /**
     * Get landline
     *
     * @return string 
     */
    public function getLandline()
    {
        return $this->landline;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return SalonsolutionsAppointment
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set scheduledDate
     *
     * @param string $scheduledDate
     * @return SalonsolutionsAppointment
     */
    public function setScheduledDate($scheduledDate)
    {
        $this->scheduledDate = $scheduledDate;
    
        return $this;
    }

    /**
     * Get scheduledDate
     *
     * @return string 
     */
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * Set scheduledTime
     *
     * @param string $scheduledTime
     * @return SalonsolutionsAppointment
     */
    public function setScheduledTime($scheduledTime)
    {
        $this->scheduledTime = $scheduledTime;
    
        return $this;
    }

    /**
     * Get scheduledTime
     *
     * @return string 
     */
    public function getScheduledTime()
    {
        return $this->scheduledTime;
    }

    /**
     * Set bookingType
     *
     * @param integer $bookingType
     * @return SalonsolutionsAppointment
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;
    
        return $this;
    }

    /**
     * Get bookingType
     *
     * @return integer 
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return SalonsolutionsAppointment
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsAppointment
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
     * @return SalonsolutionsAppointment
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
     * @return SalonsolutionsAppointment
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
     * @return SalonsolutionsAppointment
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
     * @return SalonsolutionsAppointment
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
