<?php

namespace SalonSolution\EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalonsolutionsGlobalType
 */
class SalonsolutionsGlobalType
{
    /**
     * @var string
     */
    public $type;

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
     * Set type
     *
     * @param string $type
     * @return SalonsolutionsGlobalType
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SalonsolutionsGlobalType
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
     * @return SalonsolutionsGlobalType
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
