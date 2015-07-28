<?php

namespace SalonSolution\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsSalon
 *
 * @ORM\Table(name="salonSolutions_salon")
 * @ORM\Entity
 */
class SalonsolutionsSalon
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255, nullable=true)
     */
    public $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     */
    public $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @var string
     *
     * @ORM\Column(name="advertisement_display", type="string", length=255, nullable=true)
     */
    public $advertisementDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    public $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    public $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=true)
     */
    public $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100, nullable=true)
     */
    public $country;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=15, nullable=true)
     */
    public $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=15, nullable=true)
     */
    public $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="landline", type="string", length=15, nullable=true)
     */
    public $landline;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    public $logo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_bookings_custom", type="integer", nullable=false)
     */
    public $maxBookingsCustom;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_bookings_default", type="integer", nullable=false)
     */
    public $maxBookingsDefault;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    public $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    public $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="google", type="string", length=255, nullable=true)
     */
    public $google;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=255, nullable=true)
     */
    public $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="pinterest", type="string", length=255, nullable=true)
     */
    public $pinterest;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_id", type="integer", nullable=true)
     */
    public $ownerId;

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
     * Set name
     *
     * @param string $name
     * @return SalonsolutionsSalon
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return SalonsolutionsSalon
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return SalonsolutionsSalon
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SalonsolutionsSalon
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
     * Set advertisementDisplay
     *
     * @param string $advertisementDisplay
     * @return SalonsolutionsSalon
     */
    public function setAdvertisementDisplay($advertisementDisplay)
    {
        $this->advertisementDisplay = $advertisementDisplay;
    
        return $this;
    }

    /**
     * Get advertisementDisplay
     *
     * @return string 
     */
    public function getAdvertisementDisplay()
    {
        return $this->advertisementDisplay;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * Set logo
     *
     * @param string $logo
     * @return SalonsolutionsSalon
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    
        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalonsolutionsSalon
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
     * Set maxBookingsCustom
     *
     * @param integer $maxBookingsCustom
     * @return SalonsolutionsSalon
     */
    public function setMaxBookingsCustom($maxBookingsCustom)
    {
        $this->maxBookingsCustom = $maxBookingsCustom;
    
        return $this;
    }

    /**
     * Get maxBookingsCustom
     *
     * @return integer 
     */
    public function getMaxBookingsCustom()
    {
        return $this->maxBookingsCustom;
    }

    /**
     * Set maxBookingsDefault
     *
     * @param integer $maxBookingsDefault
     * @return SalonsolutionsSalon
     */
    public function setMaxBookingsDefault($maxBookingsDefault)
    {
        $this->maxBookingsDefault = $maxBookingsDefault;
    
        return $this;
    }

    /**
     * Get maxBookingsDefault
     *
     * @return integer 
     */
    public function getMaxBookingsDefault()
    {
        return $this->maxBookingsDefault;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return SalonsolutionsSalon
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return SalonsolutionsSalon
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set google
     *
     * @param string $google
     * @return SalonsolutionsSalon
     */
    public function setGoogle($google)
    {
        $this->google = $google;
    
        return $this;
    }

    /**
     * Get google
     *
     * @return string 
     */
    public function getGoogle()
    {
        return $this->google;
    }

    /**
     * Set linkedin
     *
     * @param string $linkedin
     * @return SalonsolutionsSalon
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    
        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string 
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set pinterest
     *
     * @param string $pinterest
     * @return SalonsolutionsSalon
     */
    public function setPinterest($pinterest)
    {
        $this->pinterest = $pinterest;
    
        return $this;
    }

    /**
     * Get pinterest
     *
     * @return string 
     */
    public function getPinterest()
    {
        return $this->pinterest;
    }

    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return SalonsolutionsSalon
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    
        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set creatorId
     *
     * @param integer $creatorId
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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
     * @return SalonsolutionsSalon
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