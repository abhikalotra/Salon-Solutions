<?php

namespace SalonSolution\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsZipcode
 *
 * @ORM\Table(name="salonSolutions_zipcode")
 * @ORM\Entity
 */
class SalonsolutionsZipcode
{
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100, nullable=false)
     */
    public $country;

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
     * @ORM\Column(name="latitude", type="string", length=100, nullable=true)
     */
    public $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=100, nullable=true)
     */
    public $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=100)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $zip;



    /**
     * Set country
     *
     * @param string $country
     * @return SalonsolutionsZipcode
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
     * Set city
     *
     * @param string $city
     * @return SalonsolutionsZipcode
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
     * @return SalonsolutionsZipcode
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
     * Set latitude
     *
     * @param string $latitude
     * @return SalonsolutionsZipcode
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return SalonsolutionsZipcode
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
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
}