<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                "label" => "Give your review a nice title",
                "attr" => [
                    "placeholder" => "Shitty movie, get away",
                    "class" => "my-class"
                ]
            ])

            ->add('content', null, [
                "label" => "Your review"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['novalidate' => 'novalidate'],
            'data_class' => Review::class,
        ]);
    }
}
