<?php

namespace App\Form;

use App\Entity\Usertodo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsertodoType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => ' ',
                'attr' => [
                    'placeholder' => "NOM"
                ]
            ])
            
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => ' ',
                'attr' => [
                    'placeholder' => "EMAIL"
                ]
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => [
                    'label' => ' ',
                    'attr' => [
                        'placeholder' => "PASSWORD"
                    ]
                ],
                'second_options' => [
                    'label' => ' ',
                    'attr' => [
                        'placeholder' => "CONFIRMATION"
                    ]
                ]
            ])
            
            ->add('role', ChoiceType::class, [
                'required' => true,
				'label' => ' ',
				'choices' => [
					'ROLE USER' => 'ROLE_USER',
					'ROLE ADMIN' => 'ROLE_ADMIN'
				]
			])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usertodo::class,
        ]);
    }
}
