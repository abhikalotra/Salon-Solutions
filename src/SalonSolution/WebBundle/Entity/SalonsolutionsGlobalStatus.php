<?php

namespace SalonSolution\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsGlobalStatus
 *
 * @ORM\Table(name="salonSolutions_global_status")
 * @ORM\Entity
 */
class SalonsolutionsGlobalStatus
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=false)
     */
    public $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_name", type="string", length=50, nullable=true)
     */
    public $entityName;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;



    /**
     * Set status
     *
     * @param string $status
     * @return SalonsolutionsGlobalStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SalonsolutionsGlobalStatus
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
     * Set entityName
     *
     * @param string $entityName
     * @return SalonsolutionsGlobalStatus
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    
        return $this;
    }

    /**
     * Get entityName
     *
     * @return string 
     */
    public function getEntityName()
    {
        return $this->entityName;
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