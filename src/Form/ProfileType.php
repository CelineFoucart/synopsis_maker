<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('about', TextareaType::class, [
                'required' => false,
                'help' => 'Présentation de 15000 caractères maximum.',
            ])
            ->add('localisation', TextType::class, [
                'required' => false,
            ])
            ->add('rank', TextType::class, [
                'required' => false,
            ])
            ->add('interests', TextType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
