<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Point;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Point2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class)
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
                        'maxSizeMessage' => 'L\'image ne doit pas fair plus de 2mo'
                    ])
                ]

            ])
            ->add('horaire')
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'etablissement',
                'label' => 'Etablissement'
            ])
            ->add('id_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'category',
                'label' => 'Categorie'
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
