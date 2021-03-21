<?php

namespace App\Form;

use App\Entity\Tasktodo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TasktodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
            ->add('title', TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'TITRE'
                ]
            ]) 
            ->add('content', TextareaType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'CONTENU'
                ]
            ]) 
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tasktodo::class,
        ]);
    }
}
