<?php

namespace App\Form;

use App\Entity\Church;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ChurchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome da Igreja'
            ])
            ->add('address', TextType::class, [
                'label' => 'Endereço'
            ])
            ->add('website', TextType::class, [
                'required' => false,
                'label' => 'Site (Opcional)'
            ])
            ->add('image', FileType::class, [
                    'label' => 'Imagem da Igreja',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '64M',
                            'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            ],
                            'mimeTypesMessage' => 'Por favor, envie uma imagem válida (JPG, PNG ou WEBP)',
                        ])
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Church::class,
        ]);
    }
}
