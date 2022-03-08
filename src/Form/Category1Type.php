<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Category1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category',  TextType::class,[
                'label' => 'Catégorie',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer une catégorie',
                    ]),
                ],
                'required' => true,
            ])
            ->add('color', ChoiceType::class, [
                'choices'  => [
                    'Evenement' => 'event',
                    'Noir' => 'black',
                    'Bleu' => 'blue',
                    'Bleu Ciel' => 'blue-light',
                    'Vert Foncé' => 'dark-green',
                    'Vert' => 'green',
                    'Gris' => 'grey',
                    'Orange' => 'orange',
                    'Rose' => 'pink',
                    'Violet' => 'purple',
                    'Rouge' => 'red',
                    'Jaune' => 'yellow',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
