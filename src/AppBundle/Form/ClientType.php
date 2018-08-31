<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 8:36
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use AppBundle\Entity\Client;

class ClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('id', HiddenType::class)
            ->add('name', null, array('label' => 'Nombre*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre del producto')))
            ->add('surname1', null, array('label' => 'Primer apellido*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Primer apellido del cliente')))
            ->add('surname2', null, array('required' => false, 'data_class' => null, 'label' => 'Segundo apellido', 'attr' => array('class' => 'form-control', 'placeholder' => 'Segundo apellido del cliente')))
            ->add('dni', null, array('label' => 'Dni*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Dni del cliente')))
            ->add('province', null, array('required' => false, 'data_class' => null, 'label' => 'Provincia', 'attr' => array('class' => 'form-control', 'placeholder' => 'Provincia del cliente')))
            ->add('town', null, array('required' => false, 'data_class' => null, 'label' => 'Localidad', 'attr' => array('class' => 'form-control', 'placeholder' => 'Localidad del cliente')))
            ->add('postcode', NumberType::class, array('required' => false, 'data_class' => null, 'label' => 'Código postal', 'attr' => array('class' => 'form-control', 'placeholder' => 'Código del cliente')))
            ->add('street', null, array('required' => false, 'data_class' => null, 'label' => 'Calle', 'attr' => array('class' => 'form-control', 'placeholder' => 'Calle del cliente')))
            ->add('num_street', NumberType::class, array('required' => false, 'data_class' => null, 'label' => 'Número', 'attr' => array('class' => 'form-control', 'placeholder' => 'Nª de la calle')))
            ->add('birthdate', DateType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd', 'input' => 'timestamp', 'required' => false, 'data_class' => null, 'label' => 'Fecha de nacimiento', 'attr' => array('class' => 'form-control', 'placeholder' => 'Fecha de nacimiento del cliente')))
            //->add('birthdate',DateType::class,array('widget' => 'single_text','format' => 'yyyy-MM-dd', 'input' => 'timestamp'))
            ->add('telephone', NumberType::class, array('label' => 'Teléfono', 'attr' => array('class' => 'form-control', 'placeholder' => 'Teléfono del cliente')))
            ->add('email', EmailType::class, array('required' => false, 'data_class' => null, 'label' => 'Email', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email del cliente')))
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-success btn-lg float-right')));


        /*$builder->get('birthdate')
            ->addModelTransformer(new CallbackTransformer(
                function ($string) {
                    $dtime = \DateTime::createFromFormat("d/m/Y", $string);
                    $dtime = date('d/')
                    return $dtime->getTimestamp();
                },
                function ($timestamp) {
                    return date('d/m/Y', $timestamp);
                }
            ));*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Client::class,
        ));
    }

}