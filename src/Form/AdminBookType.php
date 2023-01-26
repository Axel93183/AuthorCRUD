<?php

namespace App\Form;


use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre du livre ',
            'required' => true,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix du livre ',
            'required' => true,
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description du livre ',
            'required' => false,
        ])
        ->add('imageUrl', UrlType::class, [
            'label' => 'URL de l\'image du livre ',
            'required' => false,
        ])
        ->add('author', EntityType::class, [
            'label' => 'Choix de l\'auteur ',
            'required' => false,
            // Spécifie l'entité que l'on veut pouvoir selectionner
            'class' => Author::class,
            // Spécifie la propriété de la classe Author que l'on veut afficher -> ICI: author.name
            'choice_label' => 'name',
        ])
        ->add('categories', EntityType::class, [
            'label' => 'Choix des catégories ',
            'required' => false,
            // Spécifie l'entité que l'on veut pouvoir selectionner
            'class' => Category::class,
            // Spécifie la propriété de la classe Author que l'on veut afficher -> ICI: author.name
            'choice_label' => 'name',
            // Utilisé pour rendre une boîte de sélection, des cases à cocher ou des radios.
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
