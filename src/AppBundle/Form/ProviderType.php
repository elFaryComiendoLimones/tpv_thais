<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 09/08/2018
 * Time: 13:19
 */

namespace AppBundle\Form;

use AppBundle\AppBundle;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Provider;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProviderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('id', HiddenType::class)
            ->add('name', null, array('label' => 'Nombre*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre del proveedor')))
            ->add('telefono', NumberType::class, array('required' => false, 'data_class' => null, 'label' => 'Teléfono', 'attr' => array('class' => 'form-control', 'placeholder' => 'Teléfono del proveedor')))
            ->add('email', EmailType::class, array('required' => false, 'data_class' => null, 'label' => 'Email', 'attr' => array('class' => 'form-control', 'placeholder' => 'Descipción del producto')))
            ->add('town', null, array('required' => false, 'data_class' => null, 'label' => 'Localidad', 'attr' => array('class' => 'form-control', 'placeholder' => 'Localidad del proveedor')))
            ->add('postcode', NumberType::class, array('required' => false, 'data_class' => null, 'label' => 'Código Postal', 'attr' => array('class' => 'form-control', 'placeholder' => 'Código postal del proveedor')))
            ->add('address', null, array('required' => false, 'data_class' => null, 'label' => 'Dirección', 'attr' => array('class' => 'form-control', 'placeholder' => 'Dirección del proveedor')))
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-success btn-lg float-right')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Provider::class,
        ));
    }

}