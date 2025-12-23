<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Church;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', null, [
                'label' => 'Logradouro',
            ])
            ->add('birthDate', null, [
                'label' => 'Data de Nascimento',
                'widget' => 'single_text',
            ])
            ->add('church', EntityType::class, [
                'choice_label' => 'name',
                'class' => Church::class,
                'label' => 'Igreja',
                'placeholder' => 'Selecione uma igreja...',
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'js-city-input'],
                'label' => 'Cidade',
            ])
            ->add('cpf', null, [
                'attr' => [
                    'class' => 'js-cpf',
                    'maxlength' => 14,
                    'placeholder' => '000.000.000-00',
                ],
                'label' => 'CPF',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
            ])
            ->add('name', null, [
                'attr' => ['placeholder' => 'Digite seu Nome'],
                'label' => 'Nome Completo',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefone',
            ])
            ->add('state', TextType::class, [
                'attr' => ['class' => 'js-state-input'],
                'label' => 'Estado',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
