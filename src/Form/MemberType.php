<?php

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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nome Completo',
                'attr' => ['placeholder' => 'Digite seu Nome']
            ])
            ->add('cpf', null, [
                'label' => 'CPF',
                'attr' => ['class' => 'cpf-mask', 'placeholder' => '000.000.000-00']
            ])
            ->add('birthDate', null, [
                'widget' => 'single_text',
                'label' => 'Data de Nascimento'
            ])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('phone', TextType::class, ['label' => 'Telefone'])
            ->add('state', TextType::class, [
                'label' => 'Estado (UF)',
                'attr' => [
                    'class' => 'js-state-input d-none',
                    'readonly' => true
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Cidade',
                'attr' => [
                    'class' => 'js-city-input d-none',
                    'readonly' => true
                ]
            ])
            ->add('address', null, ['label' => 'Logradouro'])
            ->add('church', EntityType::class, [
                'class' => Church::class,
                'choice_label' => 'name',
                'label' => 'Igreja',
                'placeholder' => 'Selecione uma igreja...',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
