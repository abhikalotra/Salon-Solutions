<?php
// src/SalonSolution/AdminBundle/Resources/SalonsolutionsUserType.php

namespace SalonSolution\AdminBundle\Resources;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SalonsolutionsCmsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('description', 'textarea');
        $builder->add('url');
        $builder->add('submit','submit');
        
        
    }
      public function getName()
    {
        return 'SalonsolutionsCms';
    }

    
}
