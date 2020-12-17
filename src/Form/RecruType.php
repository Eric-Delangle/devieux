<?php

namespace App\Form;

use App\Entity\Recruter;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RecruType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('password', PasswordType::class, [
                'attr' => [
                    'required' => false,

                ]
            ])
            ->add('password_verify', PasswordType::class, [
                'attr' => [
                    'required' => false,

                ]
            ])
            ->add('avatarFile', VichFileType::class, [
                'required' => false,
                'label' => 'Votre avatar',
            ])
            ->add('location');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recruter::class,
            'translation_domain' => 'forms'
        ]);
    }
}
