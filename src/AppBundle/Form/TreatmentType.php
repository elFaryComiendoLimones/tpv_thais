<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 19/09/2018
 * Time: 9:31
 */

namespace AppBundle\Form;

use AppBundle\Entity\Treatment;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TreatmentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('id', HiddenType::class)
            ->add('name', null, array('label' => 'Nombre*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre del tratamiento')))
            ->add('price', MoneyType::class, array('label' => 'Precio*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Precio del tratamiento')))
            ->add('description', null, array('required' => false, 'label' => 'Descripción', 'attr' => array('class' => 'form-control', 'placeholder' => 'Descripción del tratamiento')))
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-success btn-lg float-right')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Treatment::class,
        ));
    }

}