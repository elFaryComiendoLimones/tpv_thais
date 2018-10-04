<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 03/10/2018
 * Time: 18:26
 */

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class FilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('id_user', EntityType::class, array(
                'label' => 'Empleado',
                'class' => 'AppBundle:User',
                'choice_label' => 'id_user',
                'attr' => array('class' => 'form-control')
            ))
            ->add('id_client', EntityType::class, array(
                'label' => 'Cliente',
                'class' => 'AppBundle:Client',
                'choice_label' => 'id_client',
                'attr' => array('class' => 'form-control')
            ))
            ->add('date_sale', DateType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd', 'input' => 'timestamp', 'required' => false, 'data_class' => null, 'label' => 'Fecha de venta', 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Filtrar', 'attr' => array('class' => 'btn btn-primary')));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class,
        ));
    }

}