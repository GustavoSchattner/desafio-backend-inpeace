<?php

declare(strict_types=1);

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
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Endereço',
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '64M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Por favor, envie uma imagem válida (JPG, PNG ou WEBP)',
                    ]),
                ],
                'label' => 'Imagem da Igreja',
                'mapped' => false,
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nome da Igreja',
            ])
            ->add('website', TextType::class, [
                'label' => 'Site (Opcional)',
                'required' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Church::class,
        ]);
    }
}
