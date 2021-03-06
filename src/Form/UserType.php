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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('location')
            ->add('description', TextType::class, [
                'label' => 'Votre parcours'
            ])
            ->add('formation')
            ->add('loisirs')
            ->add('experience')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => 'true',
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-inline']
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
