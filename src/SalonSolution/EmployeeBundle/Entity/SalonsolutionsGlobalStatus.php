<?php

namespace SalonSolution\EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsGlobalStatus
 */
class SalonsolutionsGlobalStatus
{
    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var integer
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
