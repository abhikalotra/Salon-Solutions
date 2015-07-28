<?php
// src/SalonSolution/AdminBundle/Resources/SalonsolutionsUserType.php

namespace SalonSolution\AdminBundle\Resources;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SalonsolutionsUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('email');
        $builder->add('country');
        $builder->add('state');
        $builder->add('address');
        $builder->add('city');
        $builder->add('zip');
        $builder->add('submit','submit');
        
        
       	
        
      // print_r($builder->get('email')->getData());
    }
      public function getName()
    {
        return 'SalonsolutionsUser';
    }

    
}
