<?php

namespace App\Form;

use App\Entity\Point;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', TextType::class,[
                'label' => 'Categorie',
                'disabled' => true,
                ])
            ->add('description', TextType::class,[
                'label' => 'Déscription',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Point::class,
        ]);
    }
}
