<?php

namespace App\Form;

use App\Entity\Point;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Point::class,
        ]);
    }
}
