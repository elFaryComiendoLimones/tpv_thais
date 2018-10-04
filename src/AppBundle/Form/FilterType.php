<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 03/10/2018
 * Time: 18:26
 */

namespace AppBundle\Form;


class FilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //TODO agregar 3 campos de filtro(empleado, cliente, fecha) los dos primeros son select
        $builder
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-success btn-lg float-right')));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Client::class,
        ));
    }

}