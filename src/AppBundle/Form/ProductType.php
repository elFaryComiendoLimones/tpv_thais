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
use AppBundle\Entity\Product;
use AppBundle\Entity\Provider;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('bar_code', null, array('label' => 'Código de barras', 'attr' => array('class' => 'form-control')))
            ->add('name', null, array('label' => 'Nombre', 'attr' => array('class' => 'form-control')))
            ->add('price', MoneyType::class, array('label' => 'Precio', 'attr' => array('class' => 'form-control')))
            ->add('description', null, array('label' => 'proveedor', 'attr' => array('class' => 'form-control')))
            ->add('id_provider', EntityType::class, array(
                'label' => 'Proveedor',
                'class' => 'AppBundle:Provider',
                'choice_label' => 'name',
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-success btn-lg float-right')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

}