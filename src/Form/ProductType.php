<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre du produit'])
            ->add('content', TextareaType::class, ['label' => 'Description du produit'])
            ->add('created', DateType::class, [
                'label' => 'Ajouter de la date',
                'data' => new \DateTime(),
                'format' => 'dd MM yyyy'
            ])
            ->add('image', TextType::class, [
                'label' => 'Selectionnez de l\'image',
                'empty_data' => 'logo_headict.svg',
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => 'App:Category',
                'choice_label' => 'Category'
            ])
            ->add('price', NumberType::class, ['label' => 'Prix'])
            ->add('published', ChoiceType::class, [
                'label' => 'Publication',
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0
                ]
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
