<?php
// src/SalonSolution/AdminBundle/Resources/SalonsolutionsUserType.php

namespace SalonSolution\AdminBundle\Resources;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SalonsolutionsSalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('domain');
        $builder->add('owner_id');
        $builder->add('submit','submit');
        
        
       	
        
      // print_r($builder->get('email')->getData());
    }
      public function getName()
    {
        return 'SalonsolutionsSalon';
    }

    
}
