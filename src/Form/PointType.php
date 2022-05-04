<?php

namespace App\Form;

use App\Entity\Point;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        // ajoute un champ de type entitytype pour choisir une catégorie

            ->add('id_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'category',
                'label' => 'Categorie'
            ])

        // ajoute un champ de type text pour la description

            ->add('description', TextType::class,[
                'label' => 'Description',
            ])
        
        // ajoute un champ de type text pour les horaires

            ->add('horaire', TextType::class,[
                'label' => 'Horaires',
            ])

        // ajoute un champ de type file pour ajouter une photo aux format jpg,png et de taille maximal de 5mo
        
                ->add('img', FileType::class, [

                    'label' => 'Image',

                    'mapped' => false,
    
                    'required' => false,
    
                    'constraints' => [
                        new File([
                            'maxSize' => '5048k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'L\'image doit etre au format jpeg ou jpg',
                            'maxSizeMessage' => 'L\'image ne doit pas fair plus de 5mo'
                        ])
                    ]
    
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
