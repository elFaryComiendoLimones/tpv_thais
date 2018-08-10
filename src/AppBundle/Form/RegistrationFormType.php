<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Email', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'Introduce el email')))
            ->add('username', null, array('label' => 'Nombre', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'Introduce nombre')))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'Contraseña', 'attr' => array('class' => 'form-control', 'placeholder' => 'Introduce la contraseña')),
                'second_options' => array('label' => 'Confirmación de contraseña', 'attr' => array('class' => 'form-control', 'placeholder' => 'Repite la contraseña')),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('image', FileType::class, array('label' => 'Imagen de perfil', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control')))
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => ['Administrador' => 'ROLE_ADMIN', 'Empleado' => 'ROLE_USER'],
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'check-roles')
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_token_id' => 'registration',
        ));
    }

    // BC for SF < 3.0

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_registration';
    }
}
