<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', SearchType::class, [
                "attr" => [
                    "placeholder" => "Brad Pitt, Forrest Gump, ..."
                ],
                "required" => false,
            ])
            ->add('minYear', IntegerType::class, [
                "attr" => [
                    "placeholder" => "Min year"
                ],
                "required" => false,
            ])
            ->add('maxYear', IntegerType::class, [
                "attr" => [
                    "placeholder" => "Max year"
                ],
                "required" => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'GO',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
