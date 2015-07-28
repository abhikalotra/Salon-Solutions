<?php
// src/SalonSolution/WebBundle/Resources/SalonsolutionsUserType.php

namespace SalonSolution\WebBundle\Resources;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SalonsolutionsSalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name','text', array('required' => true));    
        $builder->add('description');             
        $builder->add('domain');   
        $builder->add('email','email');
        $builder->add('password', 'repeated', array(
						'type' => 'password',
						'invalid_message' => 'The password fields must match.',
						'options' => array('attr' => array('class' => 'password-field')),
						'required' => true,
						'first_options'  => array('label' => 'Password'),
						'second_options' => array('label' => 'Repeat Password'),
					));	
									
        $builder->add('address');
        $builder->add('city');
        $builder->add('state');
        $builder->add('country');
        $builder->add('zip');
        $builder->add('mobile');
        $builder->add('landline');
        $builder->add('photo','file');
        
       /*  $builder->add('type', 'entity', array( 'mapped'   => true, 
												'class'    => 'SalonSolutionWebBundle:SalonsolutionsGlobalType',
												'property' => 'type'												
												));    */                              //name of the php SalonsolutionsGlobalType.php
        $builder->add('submit','submit');
        

        
       //print_r($builder->get('name')->getData());
    }
      public function getName()
    {
        return 'SalonsolutionsUser';
    }

    
}
