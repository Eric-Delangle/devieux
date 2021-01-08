<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Media;
use App\Form\UserType;
use App\Form\MediaType;
use App\Form\RecruType;
use App\Entity\Category;
use App\Entity\Recruter;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterRecruterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recruter', RecruType::class, [
                'label' => false
            ])
            ->add('media', MediaType::class, [
                'label' => false
            ])

            ->add("Enregistrer", SubmitType::class);
        dump($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'forms'
        ]);
    }
}
