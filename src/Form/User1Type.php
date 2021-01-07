<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('location')
            ->add('description')
            ->add('formation')
            ->add('loisirs')
            ->add('experience')
            ->add('avatarFile', EntityType::class, [
                'class' => Media::class,
                'required' => false,
                'label' => 'Votre avatar',
            ])
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
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => 'true',
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms'
        ]);
    }
}
