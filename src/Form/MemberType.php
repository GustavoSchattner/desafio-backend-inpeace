<?php

namespace App\Form;

use App\Entity\Church;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nome Completo'])
            ->add('cpf', TextType::class, ['label' => 'CPF'])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data de Nascimento'
            ])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('phone', TextType::class, ['label' => 'Telefone'])
            ->add('address', TextType::class, ['label' => 'Logradouro'])

            ->add('state', TextType::class, [
                'label' => 'Estado (UF)',
                'attr' => ['maxlength' => 2]
            ])
            ->add('city', TextType::class, ['label' => 'Cidade'])

            // Select de Igrejas
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
