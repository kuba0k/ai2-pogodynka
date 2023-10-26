<?php

namespace App\Form;

use App\Entity\Measurement;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'html5'=>true,
            ])
            ->add('celsius', NumberType::class, [
                'attr' => [
                    'min' => -50,
                    'max' => 60,
                ]
            ])
            ->add('clouds', IntegerType::class,
            [
                'attr'=>[
                    'min'=>0,
                    'max'=>2
                ],
            ])
            ->add('humidity', NumberType::class, [
                'attr'=>[
                    'min'=>0,
                    'max'=>1
                ],
                'scale'=>2,
                'html5' => true,
            ])
            ->add('rain', NumberType::class, [
                'attr'=>[
                    'min'=>0,
                    'max'=>1
                ],
                'scale'=>2,
                'html5' => true,
            ])
            ->add('air', IntegerType::class,
                [
                    'attr'=>[
                        'min'=>0,
                        'max'=>500
                    ],
                ])
            ->add('smog', IntegerType::class,
                [
                    'attr'=>[
                        'min'=>0,
                        'max'=>2
                    ],
                ])
            ->add('location', EntityType::class, [
                'class' => 'App\Entity\Location',
                'choice_label' => 'city',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
