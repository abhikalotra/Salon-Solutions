<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsSalonImage
 */
class SalonsolutionsSalonImage
{
    /**
     * @var integer
     */
    public $salonId;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    public $caption;

    /**
     * @var integer
     */
    public $id;


    /**
     * Set salonId
     *
     * @param integer $salonId
     * @return SalonsolutionsSalonImage
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
     * Set image
     *
     * @param string $image
     * @return SalonsolutionsSalonImage
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return SalonsolutionsSalonImage
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
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
