<?php

namespace SalonSolution\SalonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsSalonHours
 */
class SalonsolutionsSalonHours
{
    /**
     * @var integer
     */
    public $salonId;

    /**
     * @var string
     */
    public $monFhalfStart;

    /**
     * @var string
     */
    public $monFhalfEnd;

    /**
     * @var string
     */
    public $monShalfStart;

    /**
     * @var string
     */
    public $monShalfEnd;

    /**
     * @var string
     */
    public $tuesFhalfStart;

    /**
     * @var string
     */
    public $tuesFhalfEnd;

    /**
     * @var string
     */
    public $tuesShalfStart;

    /**
     * @var string
     */
    public $tuesShalfEnd;

    /**
     * @var string
     */
    public $wedFhalfStart;

    /**
     * @var string
     */
    public $wedFhalfEnd;

    /**
     * @var string
     */
    public $wedShalfStart;

    /**
     * @var string
     */
    public $wedShalfEnd;

    /**
     * @var string
     */
    public $thuFhalfStart;

    /**
     * @var string
     */
    public $thuFhalfEnd;

    /**
     * @var string
     */
    public $thuShalfStart;

    /**
     * @var string
     */
    public $thuShalfEnd;

    /**
     * @var string
     */
    public $friFhalfStart;

    /**
     * @var string
     */
    public $friFhalfEnd;

    /**
     * @var string
     */
    public $friShalfStart;

    /**
     * @var string
     */
    public $friShalfEnd;

    /**
     * @var string
     */
    public $satFhalfStart;

    /**
     * @var string
     */
    public $satFhalfEnd;

    /**
     * @var string
     */
    public $satShalfStart;

    /**
     * @var string
     */
    public $satShalfEnd;

    /**
     * @var string
     */
    public $sunFhalfStart;

    /**
     * @var string
     */
    public $sunFhalfEnd;

    /**
     * @var string
     */
    public $sunShalfStart;

    /**
     * @var string
     */
    public $sunShalfEnd;

    /**
     * @var integer
     */
    public $id;


    /**
     * Set salonId
     *
     * @param integer $salonId
     * @return SalonsolutionsSalonHours
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
     * Set monFhalfStart
     *
     * @param string $monFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setMonFhalfStart($monFhalfStart)
    {
        $this->monFhalfStart = $monFhalfStart;
    
        return $this;
    }

    /**
     * Get monFhalfStart
     *
     * @return string 
     */
    public function getMonFhalfStart()
    {
        return $this->monFhalfStart;
    }

    /**
     * Set monFhalfEnd
     *
     * @param string $monFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setMonFhalfEnd($monFhalfEnd)
    {
        $this->monFhalfEnd = $monFhalfEnd;
    
        return $this;
    }

    /**
     * Get monFhalfEnd
     *
     * @return string 
     */
    public function getMonFhalfEnd()
    {
        return $this->monFhalfEnd;
    }

    /**
     * Set monShalfStart
     *
     * @param string $monShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setMonShalfStart($monShalfStart)
    {
        $this->monShalfStart = $monShalfStart;
    
        return $this;
    }

    /**
     * Get monShalfStart
     *
     * @return string 
     */
    public function getMonShalfStart()
    {
        return $this->monShalfStart;
    }

    /**
     * Set monShalfEnd
     *
     * @param string $monShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setMonShalfEnd($monShalfEnd)
    {
        $this->monShalfEnd = $monShalfEnd;
    
        return $this;
    }

    /**
     * Get monShalfEnd
     *
     * @return string 
     */
    public function getMonShalfEnd()
    {
        return $this->monShalfEnd;
    }

    /**
     * Set tuesFhalfStart
     *
     * @param string $tuesFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setTuesFhalfStart($tuesFhalfStart)
    {
        $this->tuesFhalfStart = $tuesFhalfStart;
    
        return $this;
    }

    /**
     * Get tuesFhalfStart
     *
     * @return string 
     */
    public function getTuesFhalfStart()
    {
        return $this->tuesFhalfStart;
    }

    /**
     * Set tuesFhalfEnd
     *
     * @param string $tuesFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setTuesFhalfEnd($tuesFhalfEnd)
    {
        $this->tuesFhalfEnd = $tuesFhalfEnd;
    
        return $this;
    }

    /**
     * Get tuesFhalfEnd
     *
     * @return string 
     */
    public function getTuesFhalfEnd()
    {
        return $this->tuesFhalfEnd;
    }

    /**
     * Set tuesShalfStart
     *
     * @param string $tuesShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setTuesShalfStart($tuesShalfStart)
    {
        $this->tuesShalfStart = $tuesShalfStart;
    
        return $this;
    }

    /**
     * Get tuesShalfStart
     *
     * @return string 
     */
    public function getTuesShalfStart()
    {
        return $this->tuesShalfStart;
    }

    /**
     * Set tuesShalfEnd
     *
     * @param string $tuesShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setTuesShalfEnd($tuesShalfEnd)
    {
        $this->tuesShalfEnd = $tuesShalfEnd;
    
        return $this;
    }

    /**
     * Get tuesShalfEnd
     *
     * @return string 
     */
    public function getTuesShalfEnd()
    {
        return $this->tuesShalfEnd;
    }

    /**
     * Set wedFhalfStart
     *
     * @param string $wedFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setWedFhalfStart($wedFhalfStart)
    {
        $this->wedFhalfStart = $wedFhalfStart;
    
        return $this;
    }

    /**
     * Get wedFhalfStart
     *
     * @return string 
     */
    public function getWedFhalfStart()
    {
        return $this->wedFhalfStart;
    }

    /**
     * Set wedFhalfEnd
     *
     * @param string $wedFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setWedFhalfEnd($wedFhalfEnd)
    {
        $this->wedFhalfEnd = $wedFhalfEnd;
    
        return $this;
    }

    /**
     * Get wedFhalfEnd
     *
     * @return string 
     */
    public function getWedFhalfEnd()
    {
        return $this->wedFhalfEnd;
    }

    /**
     * Set wedShalfStart
     *
     * @param string $wedShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setWedShalfStart($wedShalfStart)
    {
        $this->wedShalfStart = $wedShalfStart;
    
        return $this;
    }

    /**
     * Get wedShalfStart
     *
     * @return string 
     */
    public function getWedShalfStart()
    {
        return $this->wedShalfStart;
    }

    /**
     * Set wedShalfEnd
     *
     * @param string $wedShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setWedShalfEnd($wedShalfEnd)
    {
        $this->wedShalfEnd = $wedShalfEnd;
    
        return $this;
    }

    /**
     * Get wedShalfEnd
     *
     * @return string 
     */
    public function getWedShalfEnd()
    {
        return $this->wedShalfEnd;
    }

    /**
     * Set thuFhalfStart
     *
     * @param string $thuFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setThuFhalfStart($thuFhalfStart)
    {
        $this->thuFhalfStart = $thuFhalfStart;
    
        return $this;
    }

    /**
     * Get thuFhalfStart
     *
     * @return string 
     */
    public function getThuFhalfStart()
    {
        return $this->thuFhalfStart;
    }

    /**
     * Set thuFhalfEnd
     *
     * @param string $thuFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setThuFhalfEnd($thuFhalfEnd)
    {
        $this->thuFhalfEnd = $thuFhalfEnd;
    
        return $this;
    }

    /**
     * Get thuFhalfEnd
     *
     * @return string 
     */
    public function getThuFhalfEnd()
    {
        return $this->thuFhalfEnd;
    }

    /**
     * Set thuShalfStart
     *
     * @param string $thuShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setThuShalfStart($thuShalfStart)
    {
        $this->thuShalfStart = $thuShalfStart;
    
        return $this;
    }

    /**
     * Get thuShalfStart
     *
     * @return string 
     */
    public function getThuShalfStart()
    {
        return $this->thuShalfStart;
    }

    /**
     * Set thuShalfEnd
     *
     * @param string $thuShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setThuShalfEnd($thuShalfEnd)
    {
        $this->thuShalfEnd = $thuShalfEnd;
    
        return $this;
    }

    /**
     * Get thuShalfEnd
     *
     * @return string 
     */
    public function getThuShalfEnd()
    {
        return $this->thuShalfEnd;
    }

    /**
     * Set friFhalfStart
     *
     * @param string $friFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setFriFhalfStart($friFhalfStart)
    {
        $this->friFhalfStart = $friFhalfStart;
    
        return $this;
    }

    /**
     * Get friFhalfStart
     *
     * @return string 
     */
    public function getFriFhalfStart()
    {
        return $this->friFhalfStart;
    }

    /**
     * Set friFhalfEnd
     *
     * @param string $friFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setFriFhalfEnd($friFhalfEnd)
    {
        $this->friFhalfEnd = $friFhalfEnd;
    
        return $this;
    }

    /**
     * Get friFhalfEnd
     *
     * @return string 
     */
    public function getFriFhalfEnd()
    {
        return $this->friFhalfEnd;
    }

    /**
     * Set friShalfStart
     *
     * @param string $friShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setFriShalfStart($friShalfStart)
    {
        $this->friShalfStart = $friShalfStart;
    
        return $this;
    }

    /**
     * Get friShalfStart
     *
     * @return string 
     */
    public function getFriShalfStart()
    {
        return $this->friShalfStart;
    }

    /**
     * Set friShalfEnd
     *
     * @param string $friShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setFriShalfEnd($friShalfEnd)
    {
        $this->friShalfEnd = $friShalfEnd;
    
        return $this;
    }

    /**
     * Get friShalfEnd
     *
     * @return string 
     */
    public function getFriShalfEnd()
    {
        return $this->friShalfEnd;
    }

    /**
     * Set satFhalfStart
     *
     * @param string $satFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setSatFhalfStart($satFhalfStart)
    {
        $this->satFhalfStart = $satFhalfStart;
    
        return $this;
    }

    /**
     * Get satFhalfStart
     *
     * @return string 
     */
    public function getSatFhalfStart()
    {
        return $this->satFhalfStart;
    }

    /**
     * Set satFhalfEnd
     *
     * @param string $satFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setSatFhalfEnd($satFhalfEnd)
    {
        $this->satFhalfEnd = $satFhalfEnd;
    
        return $this;
    }

    /**
     * Get satFhalfEnd
     *
     * @return string 
     */
    public function getSatFhalfEnd()
    {
        return $this->satFhalfEnd;
    }

    /**
     * Set satShalfStart
     *
     * @param string $satShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setSatShalfStart($satShalfStart)
    {
        $this->satShalfStart = $satShalfStart;
    
        return $this;
    }

    /**
     * Get satShalfStart
     *
     * @return string 
     */
    public function getSatShalfStart()
    {
        return $this->satShalfStart;
    }

    /**
     * Set satShalfEnd
     *
     * @param string $satShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setSatShalfEnd($satShalfEnd)
    {
        $this->satShalfEnd = $satShalfEnd;
    
        return $this;
    }

    /**
     * Get satShalfEnd
     *
     * @return string 
     */
    public function getSatShalfEnd()
    {
        return $this->satShalfEnd;
    }

    /**
     * Set sunFhalfStart
     *
     * @param string $sunFhalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setSunFhalfStart($sunFhalfStart)
    {
        $this->sunFhalfStart = $sunFhalfStart;
    
        return $this;
    }

    /**
     * Get sunFhalfStart
     *
     * @return string 
     */
    public function getSunFhalfStart()
    {
        return $this->sunFhalfStart;
    }

    /**
     * Set sunFhalfEnd
     *
     * @param string $sunFhalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setSunFhalfEnd($sunFhalfEnd)
    {
        $this->sunFhalfEnd = $sunFhalfEnd;
    
        return $this;
    }

    /**
     * Get sunFhalfEnd
     *
     * @return string 
     */
    public function getSunFhalfEnd()
    {
        return $this->sunFhalfEnd;
    }

    /**
     * Set sunShalfStart
     *
     * @param string $sunShalfStart
     * @return SalonsolutionsSalonHours
     */
    public function setSunShalfStart($sunShalfStart)
    {
        $this->sunShalfStart = $sunShalfStart;
    
        return $this;
    }

    /**
     * Get sunShalfStart
     *
     * @return string 
     */
    public function getSunShalfStart()
    {
        return $this->sunShalfStart;
    }

    /**
     * Set sunShalfEnd
     *
     * @param string $sunShalfEnd
     * @return SalonsolutionsSalonHours
     */
    public function setSunShalfEnd($sunShalfEnd)
    {
        $this->sunShalfEnd = $sunShalfEnd;
    
        return $this;
    }

    /**
     * Get sunShalfEnd
     *
     * @return string 
     */
    public function getSunShalfEnd()
    {
        return $this->sunShalfEnd;
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
